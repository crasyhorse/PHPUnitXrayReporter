<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Parser;

use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Test;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestExecution;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestBuilder;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestInfoBuilder;
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
     * @var TestExecution
     */
    private $testExecutionToImport;

    /**
     * @var TestExecution[]
     */
    private $testExecutionsToUpdate;

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
     * Fired after the parser has finished parsing the doc blocks. Can be overriden
     * by a developer to gain access to the list of parsed annotations.
     *
     * @return array
     */
    public function afterDocBlockParsedHook(array $meta): array
    {
        return $meta;
    }

    /**
     * Fired after the parser has finished creating the parse tre with all test executions.
     * Can be overriden by a developer to gain access to the parse tree.
     *
     * @return array
     */
    public function afterParseTreeCreatedHook(array $parseTree): array
    {
        return $parseTree;
    }

    final public function getTestExecutionToImport(): TestExecution {
        return $this->testExecutionToImport;
    }

    final public function getTestExecutionsToUpdate(): array {
        return array_values($this->testExecutionsToUpdate);
    }
    /**
     * Build parse tree, grouped by test execution. Iterations are also grouped
     * by test.
     *
     * @param array<array-key, object>
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
            // TODO Error Handling verbessern
            trigger_error($e->__toString(), E_USER_WARNING);

            return [];
        }
    }

    private function addTestExecution(array $testExecution): void
    {
        if (array_key_exists('XRAY-testExecutionKey', $testExecution)) {
            $this->testExecutionsToUpdate[$testExecution['XRAY-testExecutionKey']] = 
                new TestExecution($testExecution['XRAY-testExecutionKey']);
        } else {
            $this->testExecutionToImport = new TestExecution();
        }
    }

    private function buildTest(array $result): Test
    {
        $test = (new TestBuilder())
                ->setTestKey($result['XRAY-TESTS-testKey'])
                ->setStart($result['start'])
                ->setFinish($result['finish'])
                ->setStatus($result['status']);

        if (array_key_exists('XRAY-TESTS-comment', $result)) {
            $test = $test->setComment($result['XRAY-TESTS-comment']);
        }

        if (array_key_exists('XRAY-TESTS-defects', $result)) {
            $test = $test->setDefects($result['XRAY-TESTS-defects']);
        }

        $testInfo = $this->buildTestInfo($result);
        $test = $test->setTestInfo($testInfo);

        return $test->build();
    }

    /**
     * Builds the Xray type "TestInfo" object.
     *
     * @return TestInfo
     */
    private function buildTestInfo(array $result): TestInfo
    {
        $testInfo = new TestInfoBuilder();
        if (array_key_exists('XRAY-TESTINFO-projectKey', $result)) {
            $testInfo = $testInfo->setProjectKey($result['XRAY-TESTINFO-projectKey']);
        }

        if (array_key_exists('XRAY-TESTINFO-testType', $result)) {
            $testInfo = $testInfo->setTestType($result['XRAY-TESTINFO-testType']);
        }
        
        if (array_key_exists('XRAY-TESTINFO-requirementKeys', $result)) {
            $testInfo = $testInfo->setRequirementKeys($result['XRAY-TESTINFO-requirementKeys']);
        }

        if (array_key_exists('XRAY-TESTINFO-labels', $result)) {
            $testInfo = $testInfo->setLabels($result['XRAY-TESTINFO-labels']);
        }

        if (array_key_exists('XRAY-TESTINFO-definition', $result)) {
            $testInfo = $testInfo->setDefinition($result['XRAY-TESTINFO-definition']);
        }

        return $testInfo->build();
    }

    private function groupByTestExecutions(array $parsedResults): void
    {
        foreach ($parsedResults as $testExecution) {
            $this->addTestExecution($testExecution);
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
        foreach($parsedResults as $result){
            $test = $this->buildTest($result);

            if (array_key_exists('XRAY-testExecutionKey', $result)) {
                $testExecutions[$result['XRAY-testExecutionKey']]->addTest($test);
            } else {
                $this->testExecutionToImport->addTest($test);
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
        $testName = $matches[0][0];

        return $testName;
    }
}
