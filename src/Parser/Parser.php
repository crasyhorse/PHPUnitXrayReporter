<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Parser;

use Adbar\Dot;
use CrasyHorse\PhpunitXrayReporter\Config\Config;
use CrasyHorse\PhpunitXrayReporter\Exceptions\InvalidArgumentException;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use CrasyHorse\PhpunitXrayReporter\Xray\Builder\BuilderHandler;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use CrasyHorse\PhpunitXrayReporter\Xray\Types\TestExecution;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use ReflectionException;
use ReflectionMethod;

/**
 * Encapsulates jasny/phpdoc-parser and performs transformation of parsed data into objects.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class Parser
{
    /**
     * @var BuilderHandler
     */
    private $builderHandler;

    /**
     * @var Config
     */
    private $config;

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
     * @param string                    $configDir            Location of the configuration file
     * @param array<array-key, string>  $whitelistedTags      Allow additional tags like @test or @dataProvider
     * @param array<array-key, string>  $blacklistedTags      Remove tags like @param or @return
     * @param array<array-key, XrayTag> $additionalCustomTags Additional Tags of type XrayTag
     */
    public function __construct(string $configDir, array $whitelistedTags = [], array $blacklistedTags = [], array $additionalCustomTags = [])
    {
        $tagSet = new TagSet($whitelistedTags, $blacklistedTags);
        $this->customTags = $tagSet->getCustomTags($additionalCustomTags);
        $this->config = new Config($configDir);
        $this->builderHandler = new BuilderHandler($this->config);
    }

    /**
     * Fired after the parser has finished parsing the doc blocks. Can be overwritten
     * by a developer to gain access to the list of parsed annotations.
     *
     * @param array<array-key, string> $parsedResults The list of parsed test results
     *
     * @return array<array-key, string>
     */
    public function afterDocBlockParsedHook($parsedResults)
    {
        return $parsedResults;
    }

    /**
     * Fired after the parser has finished creating the parse tree with all test executions.
     * Can be overriden by a developer to gain access to the parse tree.
     *
     * @param array<array-key,TestExecution> $parseTree
     *
     * @return array<array-key,TestExecution>
     */
    public function afterParseTreeCreatedHook($parseTree)
    {
        return $parseTree;
    }

    /**
     * Returns the merged test execution list of testExecutionsToUpdate (test executions with testExecutionKey)
     * and testExecutionToImport (test execution without a testExecutionKey attribute).
     *
     * @return array<array-key,TestExecution>
     */
    final public function getTestExecutions()
    {
        $array = [];
        $dot = new Dot($array);
        $dot->mergeRecursive($this->testExecutionsToUpdate);
        $dot->mergeRecursive([$this->testExecutionToImport]);

        /** @var array<array-key,TestExecution> $mergedTestExecutions */
        $mergedTestExecutions = $dot->all();
        return $mergedTestExecutions;
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
     * @return array<array-key,TestExecution>|null
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
    final public function groupResults($parsedResults): void
    {
        $this->groupByTestExecutions($parsedResults);
        $this->fillTestExecutions($parsedResults);
    }

    /**
     * Parses the doc block of a method and finds all defined annotations with it's corresponding value.
     *
     * @param TestResult $result The result of a single PHPUnit spec
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

            $parsedAnnotations = $parser->parse($docBlock);
            $parsedAnnotations = $this->addTestResult($result, $parsedAnnotations);

            return $parsedAnnotations;
        } catch (ReflectionException $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Fills the testExecutions with the related tests. The prevention of the doubling of iterations
     * is handled in the addTest()-method of TestExecution object.
     *
     * 1) If testExecutionKey (TEKey) is given in the doc block of the PHPunit test, it is added to the related TEKey.
     * 2) Otherwise if the TEKey is given in the config file for this extension, this TEKey corresponding TE is filled.
     * 3) Else, the TE to import is filled
     *
     * @param array<array-key, string> $parsedResults The list of parsed/processed test results
     *
     * @return void
     */
    private function fillTestExecutions($parsedResults): void
    {
        /** @var array<array-key, string> $result */
        foreach ($parsedResults as $result) {
            try {
                $test = $this->builderHandler->buildTest($result);
                $dotResult = new Dot($result);

                /** @var string|null $testExecutionKey */
                $testExecutionKey = $dotResult->get('XRAY-testExecutionKey') ?? $this->config->get('testExecutionKey');

                if ($testExecutionKey) {
                    /** @var string $testExecutionKey */
                    $testExecution = $this->testExecutionsToUpdate[$testExecutionKey];
                    $testExecution->addTest($test);
                } elseif (!empty($this->testExecutionToImport)) {
                    $this->testExecutionToImport->addTest($test);
                }
            } catch (InvalidArgumentException $e) {
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }
    }

    /**
     * Walks over the array of parsed results and creates the list of test executions
     * (parse tree). The BuilderHandler acts like this for every doc block:.
     *
     * 1) If testExecutionKey (TEKey) is given in the doc block comment of the PHPunit test,
     *    an TE is created with this exact key.
     * 2) Otherwise if the TEKey is given in the config file for this extension, an TE is
     *    created with the TEKey from the config.
     * 3) Else, a TE without a Key is created. This special TE is the only one who gets the Info Object.
     *
     * @param array<array-key, string> $parsedResults
     *
     * @return void
     */
    private function groupByTestExecutions(array $parsedResults): void
    {
        /** @var array<array-key, string> $result */
        foreach ($parsedResults as $result) {
            try {
                $testExecution = $this->builderHandler->buildTestExecution($result);

                if ($testExecution) {
                    /** @var string $key */
                    $key = $testExecution->getKey();
                    $this->testExecutionsToUpdate[$key] = $testExecution;
                } else {
                    $this->testExecutionToImport = new TestExecution();
                    $this->testExecutionToImport->addInfo($this->builderHandler->buildInfo());
                }
            } catch (InvalidArgumentException $e) {
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }
    }

    /**
     * Reads the attributes from the test result and adds them to the $meta object.
     *
     * @param TestResult $result The encapsulated result of a PHPUnit spec.
     * @param array<array-key, mixed> $parsedAnnotations Informations from XRAY-Annotations
     *
     * @return array
     */
    private function addTestResult(TestResult $result, $parsedAnnotations): array
    {
        $parsedAnnotations['start'] = $result->getStart();
        $parsedAnnotations['finish'] = $result->getFinish();
        $parsedAnnotations['comment'] = $result->getMessage() ?? 'Test has passed.';
        $parsedAnnotations['status'] = $result->getStatus();
        $parsedAnnotations['name'] = $result->getName();

        return $parsedAnnotations;
    }

    /**
     * Strips off the "with data set ..." string from the test name.
     *
     * @param string $test The textual representation of a PHPUnit spec
     *
     * @return string
     */
    private function stripOffWithDataSet(string $test): string
    {
        preg_match_all('/([[:alpha:]][_0-9a-zA-Z:\\\]+)(?!< with data set)/', $test, $matches);

        return $matches[0][0];
    }
}
