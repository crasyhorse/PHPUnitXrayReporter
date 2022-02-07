<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Parser;

use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Info;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Test;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestExecution;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use Exception;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use OutOfBoundsException;
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
    private $testExecution;

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
     * Build parse tree, grouped by test execution. Iterations are also grouped
     * by test.
     *
     * @param array<array-key, object>
     *
     * @return array
     */
    final public function groupResults(array $parsedResults): array
    {
        $groupedResults = $this->groupByTestExecutions($parsedResults);

        return $groupedResults;
        // $groupedResults = $this->groupIterations($parsedResults);
        // $testExecutions = $this->buildTestExecutions($groupedResults);
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

    /**
     * Builds the Xray type "Info" object.
     *
     * @return Info
     */
    private function buildInfo(array $testExecution): Info
    {
        // code...
    }

    /**
     * Builds the Xray type "TestExecution" object(s).
     *
     * @return array<array-key, TestExecution>
     */
    private function buildTestExecutions(array $groupedResults): array
    {
        $testExecutions = [];
        foreach ($groupedResults as $testExecution) {
            $info = $this->buildInfo($testExecution);
            $tests = $this->buildTests($testExecution);
            $testExecutionKey = '';
            $testExecutions[] = new TestExecution($testExecutionKey, $info, $tests);
        }

        return $testExecutions;
    }

    /**
     * Builds the Xray type "TestInfo" object.
     *
     * @return TestInfo
     */
    private function buildTestInfo(array $test): TestInfo
    {
        // code...
    }

    /**
     * Builds the Xray type "Test" object.
     *
     * @return array<array-key, Test>
     */
    private function buildTests(array $testExecution): array
    {
        // code...
    }

    private function groupByTestExecutions(array $parsedResults): array
    {
        $groupedList = [];
        foreach ($parsedResults as $result) {
            if (array_key_exists('XRAY-testExecutionKey', $result)) {
                $executionKey = $result['XRAY-testExecutionKey'];
                // TODO Wenn wir den testExecutionKey nicht mehr in dem Test Array benötigen
                // unset($result['XRAY-testExecutionKey']);
                if (array_key_exists($executionKey, $groupedList)) {
                    // $groupedList[$executionKey][] = $result; // without iteration prevention
                    $groupedList[$executionKey] = $this->groupIterations($groupedList[$executionKey], $result);
                } else {
                    $groupedList[$executionKey][] = $result;
                }
            } else {
                // TODO: Exception schreiben, falls ein Test keinen genauen testExecutionkey hat? Oder keyless Schlüssel?
                $groupedList['keyless'][] = $result;
            }
        }

        return $groupedList;
    }

    /**
     * Look for iterations and group them by test key. Also define the real
     * test result. If a single iteration has failed the whole test has to
     * be marked as failed.
     *
     * @return array
     */
    private function groupIterations(array $sortedExecutionTests, array $possibleIteration): array
    {
        $iterationPreventedArray = $sortedExecutionTests;
        $counter = 0;
        foreach ($sortedExecutionTests as $test) {
            if ($test['XRAY-TESTS-testKey'] == $possibleIteration['XRAY-TESTS-testKey']) {
                if ($possibleIteration['status'] == 'FAIL') {
                    $iterationPreventedArray[$counter] = $possibleIteration;
                }
                $counter = -1;
                break;
            }
            ++$counter;
        }
        if ($counter != -1) {
            $iterationPreventedArray[] = $possibleIteration;
        }

        return $iterationPreventedArray;
    }

    /**
     * @return string|never
     *
     * @throws OutOfBoundsException
     */
    private function readTestExecutionKey(array $parsedResults)
    {
        if (count($parsedResults) > 0) {
            if (array_key_exists('XRAY-testExecutionKey', $parsedResults[0])) {
                return $parsedResults[0]['XRAY-testExecutionKey'];
            }
        }

        throw new OutOfBoundsException('Test execution key could not be found.');
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
