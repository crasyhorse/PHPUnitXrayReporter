<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Parser;

use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestBuilder;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestInfoBuilder;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Test;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestExecution;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use InvalidArgumentException;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use ReflectionException;
use ReflectionMethod;

/**
 * Encapsulates jasny/phpdoc-parser.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class Parser
{
    /**
     * @var array<array-key, XrayTag>
     */
    private $customTags;

    /**
     * @var TestExecution|null
     */
    private $testExecutionToImport;

    /**
     * @var TestExecution[]
     */
    private $testExecutionsToUpdate = [];

    /**
     * @param array<array-key, string>  $whitelistedTags      Allow additional tags like @test or @dataProvider
     * @param array<array-key, string>  $blacklistedTags      Remove tags like @param or @return
     * @param array<array-key, XrayTag> $additionalCustomTags Additional Tags of type XrayTag
     */
    public function __construct(array $whitelistedTags = [], array $blacklistedTags = [], array $additionalCustomTags = [])
    {
        $tagSet = new TagSet($whitelistedTags, $blacklistedTags);
        $this->customTags = $tagSet->getCustomTags($additionalCustomTags);
    }

    /**
     * Fired after the parser has finished parsing the doc blocks. Can be overwritten
     * by a developer to gain access to the list of parsed annotations.
     *
     * @param array<array-key, string> $meta
     *
     * @return array<array-key, string>
     */
    public function afterDocBlockParsedHook($meta)
    {
        return $meta;
    }

    /**
     * Fired after the parser has finished creating the parse tree with all test executions.
     * Can be overriden by a developer to gain access to the parse tree.
     *
     * @param list<TestExecution> $parseTree
     *
     * @return list<TestExecution>
     */
    public function afterParseTreeCreatedHook($parseTree)
    {
        return $parseTree;
    }

    /**
     * Returns the merged test execution list of testExecutionsToUpdate and testExecutionToImport.
     *
     * @return list<TestExecution>
     */
    final public function getTestExecutionList()
    {
        $allTestExecutions = [];
        if (!empty($this->testExecutionsToUpdate)) {
            $allTestExecutions = array_merge($allTestExecutions, $this->testExecutionsToUpdate);
        }
        if (!empty($this->testExecutionToImport)) {
            $allTestExecutions[] = $this->testExecutionToImport;
        }

        return $allTestExecutions;
    }

    /**
     * Returns a single test execution that should be imported into Xray (test execution
     * without a testExecutionKey attribute).
     *
     * @return TestExecution|null
     */
    final public function getTestExecutionToImport()
    {
        return $this->testExecutionToImport;
    }

    /**
     * Returns the list of test executions (parse Tree).
     *
     * @return list<TestExecution>|null
     */
    final public function getTestExecutionsToUpdate()
    {
        return array_values($this->testExecutionsToUpdate);
    }

    /**
     * Build parse tree, grouped by test execution. Iterations are also grouped
     * by test.
     *
     * @param array<array-key, string> $parsedResults
     *
     * @return void
     */
    final public function groupResults(array $parsedResults): void
    {
        $this->groupByTestExecutions($parsedResults);
        $this->groupIterations($parsedResults, $this->testExecutionsToUpdate);
    }

    /**
     * Parses the doc block of a method.
     *
     * @return array
     */
    final public function parse(TestResult $result): array
    {
        $testName = $this->stripOffWithDataSet($result->getTest());
        try {
            $docBlock = (new ReflectionMethod($testName))->getDocComment();
            $tags = PhpDocumentor::tags()->with($this->customTags);
            $parser = new PhpdocParser($tags);

            $meta = $parser->parse($docBlock);
            $meta = $this->readTestResult($result, $meta);

            return $meta;
        } catch (ReflectionException $e) {
            trigger_error($e->__toString(), E_USER_WARNING);

            return [];
        }
    }

    /**
     * Builds the Xray type "TestExecution". If the TestExecution object has a
     * testExecutionKey attribute, it will be added to the list of updatable
     * test executions. Otherwise, it is treated as a new test executions
     * that should be imported into Xray. If the annotation is given without an attribute, an exception will be thrown.
     *
     * @param array<array-key, string> $testExecution
     *
     * @return void
     */
    private function buildTestExecution(array $testExecution): void
    {
        if (array_key_exists('XRAY-testExecutionKey', $testExecution)) {
            $testExecutionKey = $testExecution['XRAY-testExecutionKey'];
            if (empty($testExecutionKey)) {
                throw new InvalidArgumentException('XRAY-testExecutionKey has to be set, if annotation is given. Have you forgotten it? It is not set in test case: '.$testExecution['name']);
            }
            $this->testExecutionsToUpdate[$testExecutionKey] =
                new TestExecution($testExecutionKey);
        } else {
            $this->testExecutionToImport = new TestExecution();
        }
    }

    /**
     * Builds the Xray type "Test" object.
     *
     * @param array<array-key, string> $result
     *
     * @return Test
     */
    private function buildTest(array $result): Test
    {
        $test = (new TestBuilder())
                ->setName($result['name'])
                ->setStart($result['start'])
                ->setFinish($result['finish'])
                ->setComment($result['comment']);

        /** @var "PASS" | "FAIL" $status */
        $status = $result['status'];

        $test = $test->setStatus($status);

        if (array_key_exists('XRAY-TESTS-testKey', $result)) {
            $testKey = $result['XRAY-TESTS-testKey'];
            if (empty($testKey)) {
                throw new InvalidArgumentException('XRAY-TESTS-testKey has to be set, if annotation is given. Have you forgotten it? It is not set in test case: '.$result['name']);
            }
            $test = $test->setTestKey($testKey);
        }

        if (!empty($result['XRAY-TESTS-defects'])) {
            /** @var array<array-key, string> $defects */
            $defects = $result['XRAY-TESTS-defects'];
            $test = $test->setDefects($defects);
        }

        $testInfo = $this->buildTestInfo($result);
        $test = $test->setTestInfo($testInfo);

        return $test->build();
    }

    /**
     * Builds the Xray type "TestInfo" object.
     *
     * @param array<array-key,string> $result
     *
     * @return TestInfo
     */
    private function buildTestInfo(array $result): TestInfo
    {
        $testInfo = new TestInfoBuilder();
        if (!empty($result['XRAY-TESTINFO-projectKey'])) {
            $projectKey = $result['XRAY-TESTINFO-projectKey'];
            $testInfo = $testInfo->setProjectKey($projectKey);
        } elseif (!empty($result['XRAY-testExecutionKey'])) {
            $projectKey = $this->stripOfKeyNumber($result['XRAY-testExecutionKey']);
            $testInfo = $testInfo->setProjectKey($projectKey);
        } elseif (!empty($result['XRAY-TESTS-testKey'])) {
            $projectKey = $this->stripOfKeyNumber($result['XRAY-TESTS-testKey']);
            $testInfo = $testInfo->setProjectKey($projectKey);
        } else {
            throw new InvalidArgumentException('No projectKey could be found or generated in test case: '.$result['name']);
        }

        if (!empty($result['XRAY-TESTINFO-testType'])) {
            /** @var "Generic" | "Cumcumber" | null $testType */
            $testType = $result['XRAY-TESTINFO-testType'];
            $testInfo = $testInfo->setTestType($testType);
        }

        if (!empty($result['XRAY-TESTINFO-requirementKeys'])) {
            /** @var array<array-key, string> $requirementKeys */
            $requirementKeys = $result['XRAY-TESTINFO-requirementKeys'];
            $testInfo = $testInfo->setRequirementKeys($requirementKeys);
        }

        if (!empty($result['XRAY-TESTINFO-labels'])) {
            /** @var array<array-key, string> $labels */
            $labels = $result['XRAY-TESTINFO-labels'];
            $testInfo = $testInfo->setLabels($labels);
        }

        if (!empty($result['XRAY-TESTINFO-definition'])) {
            $definition = $result['XRAY-TESTINFO-definition'];
            $testInfo = $testInfo->setDefinition($definition);
        }

        if (!empty($result['summery'])) {
            $summary = $result['summery'];
            $testInfo = $testInfo->setSummary($summary);
        } else {
            $summary = $result['name'];
            $testInfo = $testInfo->setSummary($summary);
        }

        if (!empty($result['description'])) {
            $description = $result['description'];
            $testInfo = $testInfo->setDescription($description);
        }

        return $testInfo->build();
    }

    /**
     * Walks over the array of parsed results and creates the list of test executions
     * (parse tree).
     *
     * @param array<array-key, string> $parsedResults
     *
     * @return void
     */
    private function groupByTestExecutions(array $parsedResults): void
    {
        /** @var array<array-key, string> $testExecution */
        foreach ($parsedResults as $testExecution) {
            $this->buildTestExecution($testExecution);
        }
    }

    /**
     * Look for iterations and group them by test key. Also define the real
     * test result. If a single iteration has failed the whole test has to
     * be marked as failed.
     *
     * @return void
     */
    private function groupIterations(array $parsedResults, array $testExecutions): void
    {
        /** @var array<array-key, string> $result */
        foreach ($parsedResults as $result) {
            $test = $this->buildTest($result);

            if (array_key_exists('XRAY-testExecutionKey', $result)) {
                $testExecutionKey = $result['XRAY-testExecutionKey'];
                /** @var TestExecution $testExecution */
                $testExecution = $testExecutions[$testExecutionKey];
                $testExecution->addTest($test);
            } else {
                if (!empty($this->testExecutionToImport)) {
                    $this->testExecutionToImport->addTest($test);
                }
            }
        }
    }

    /**
     * Reads the attributes from the test result and adds them to the $meta object.
     *
     * @return array
     */
    private function readTestResult(TestResult $result, array $meta): array
    {
        $meta['start'] = $result->getStart();
        $meta['finish'] = $result->getFinish();
        $meta['comment'] = $result->getMessage() ?? 'Test has passed.';
        $meta['status'] = $result->getStatus();
        $meta['name'] = $result->getName();

        return $meta;
    }

    /**
     * Strips off the "with data set ..." string from the test name.
     *
     * @return string
     */
    private function stripOffWithDataSet(string $test): string
    {
        preg_match_all('/([[:alpha:]][_0-9a-zA-Z:\\\]+)(?!< with data set)/', $test, $matches);

        return $matches[0][0];
    }

    /**
     * Strips off the number behind the testExecutionKey or testKey. Afterwards the projectKey will be returned.
     *
     * @return string
     */
    private function stripOfKeyNumber(string $key): string
    {
        preg_match('/([0-9a-zA-Z]+)(?=-)/', $key, $matches);

        return $matches[0];
    }
}
