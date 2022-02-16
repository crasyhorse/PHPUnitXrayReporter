<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Parser;

use Crasyhorse\PhpunitXrayReporter\Config;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\BuilderHandler;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestExecution;
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
     * @var Config
     */
    private $config;

    /**
     * @var BuilderHandler
     */
    private $builderHandler;

    /**
     * @param array<array-key, string>  $whitelistedTags      Allow additional tags like @test or @dataProvider
     * @param array<array-key, string>  $blacklistedTags      Remove tags like @param or @return
     * @param array<array-key, XrayTag> $additionalCustomTags Additional Tags of type XrayTag
     */
    public function __construct(string $configDir, array $whitelistedTags = [], array $blacklistedTags = [], array $additionalCustomTags = [])
    {
        $tagSet = new TagSet($whitelistedTags, $blacklistedTags);
        $this->customTags = $tagSet->getCustomTags($additionalCustomTags);
        $this->config = new Config($configDir);
        $this->builderHandler = new BuilderHandler();
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
    final public function getMergedTestExecutionList()
    {
        $mergedTestExecutions = [];
        if (!empty($this->testExecutionsToUpdate)) {
            $mergedTestExecutions = array_merge($mergedTestExecutions, $this->testExecutionsToUpdate);
        }
        if (!empty($this->testExecutionToImport)) {
            $mergedTestExecutions[] = $this->testExecutionToImport;
        }

        return $mergedTestExecutions;
    }

    /**
     * Returns a single test execution that should be imported into Xray (test execution
     * without a testExecutionKey attribute).
     *
     * @return TestExecution|null
     */
    // TODO If $this->testExecutionToImport is null, an error will be thrown. It is much saver to call getMergedTestExecutionList()
    // to get every testExecution to generate the different json files
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
        $this->fillTestExecutions($parsedResults);
    }

    /**
     * Parses the doc block of a method and finds all defined annotations with it's corresponding value.
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
     * Walks over the array of parsed results and creates the list of test executions
     * (parse tree). The BuilderHandler acts like this for every doc block:.
     *
     * 1) If testExecutionKey (TEKey) is given in the doc block comment of the PHPunit test, an TE is created with this exact key.
     * 2) Otherwise if the TEKey is given in the config file for this extension, an TE is created with the TEKey from the config.
     * 3) Else, a TE without a Key is created. This special TE is the only one who gets the Info Object.
     *
     * @param array<array-key, string> $parsedResults
     *
     * @return void
     */
    private function groupByTestExecutions(array $parsedResults): void
    {
        /** @var array<array-key, string> $testExecution */
        foreach ($parsedResults as $testExecution) {
            $this->builderHandler->buildTestExecution($this->config, $testExecution, $this->testExecutionsToUpdate, $this->testExecutionToImport);
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
     * @return void
     */
    private function fillTestExecutions(array $parsedResults): void
    {
        /** @var array<array-key, string> $result */
        foreach ($parsedResults as $result) {
            $test = $this->builderHandler->buildTest($this->config, $result);

            if (array_key_exists('XRAY-testExecutionKey', $result)) {
                $testExecutionKey = $result['XRAY-testExecutionKey'];
                /** @var TestExecution $testExecution */
                $testExecution = $this->testExecutionsToUpdate[$testExecutionKey];
                $testExecution->addTest($test);
            } elseif (!empty($this->config->getTestExecutionKey())) {
                $testExecutionKey = $this->config->getTestExecutionKey();
                /** @var TestExecution $testExecution */
                $testExecution = $this->testExecutionsToUpdate[$testExecutionKey];
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
}
