<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Reporter;

use CrasyHorse\PhpunitXrayReporter\Parser\Parser;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use CrasyHorse\PhpunitXrayReporter\Xray\Types\TestExecution;

/**
 * Processes test results and the meta information from the annotations.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class Reporter
{
    /**
     * @var string
     */
    private $outputDir;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var array<array-key, TestResult>
     */
    private $testResults;

    /**
     * @param array<array-key, string>  $whitelistedTags      Allow additional tags like @test or @dataProvider
     * @param array<array-key, string>  $blacklistedTags      Remove tags like @param or @return
     * @param array<array-key, XrayTag> $additionalCustomTags Additional Tags of type XrayTag
     */
    public function __construct(string $outputDir, string $configDir, array $whitelistedTags = [], array $blacklistedTags = [], array $additionalCustomTags = [])
    {
        $this->outputDir = $outputDir;
        $this->parser = new Parser($configDir, $whitelistedTags, $blacklistedTags, $additionalCustomTags);
        $this->testResults = [];
    }

    /**
     * Add a new test result to $testResults.
     *
     * @param TestResult $testResult A single TestResult object
     * 
     * @return void
     */
    final public function add(TestResult $testResult): void
    {
        $this->testResults[] = $testResult;
    }

    /**
     * Parse and process the list of test results.
     *
     * @return void
     */
    final public function processResults(): void
    {
        /** @var array<array-key, string> $parsedResults */
        $parsedResults = $this->parseResults();
        $parsedResults = $this->parser->afterDocBlockParsedHook($parsedResults);

        $this->parser->groupResults($parsedResults);
        $parseTree = $this->parser->getTestExecutions();
        $this->createJsonFiles($parseTree);
    }

    /**
     * Creates the JSON-Files for the Xray API.
     *
     * @param array<array-key,TestExecution> $parseTree A list of objects of type TestExecution representing the 
     * results of a single test run.
     * 
     * @return void
     */
    private function createJsonFiles($parseTree): void
    {
        $parseTreeValues = array_values($parseTree);
        $outputPath = $this->outputDir . DIRECTORY_SEPARATOR;
        $filename = 'newExecution.json';
        
        foreach ($parseTreeValues as $execution) {
            if ($execution->getKey()) {
                $filename = "{$execution->getKey()}.json";
            }

            file_put_contents("{$outputPath}{$filename}", json_encode($execution, JSON_PRETTY_PRINT));
        }
    }

    /**
     * Parses test results.
     *
     * @return array
     */
    private function parseResults(): array
    {
        $parsedResults = [];
        foreach ($this->testResults as $result) {
            $parsedResults[] = $this->parser->parse($result);
        }

        return $parsedResults;
    }
}
