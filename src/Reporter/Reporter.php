<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter;

use Crasyhorse\PhpunitXrayReporter\Parser\Parser;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

/**
 * Processes test results and the meta information for the annotations.
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
    public function __construct(string $outputDir, array $whitelistedTags = [], array $blacklistedTags = [], array $additionalCustomTags = [])
    {
        $this->outputDir = $outputDir;
        $this->parser = new Parser($whitelistedTags, $blacklistedTags, $additionalCustomTags);
        $this->testResults = [];
    }

    /**
     * Add a new test result to $testResults.
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
        $parsedResults = $this->parseResults();
        $parsedResults = $this->parser->afterDocBlockParsedHook($parsedResults);

        $this->parser->groupResults($parsedResults);
        $parseTree = $this->parser->getTestExecutionsToUpdate();
        $parseTree = $this->parser->afterParseTreeCreatedHook($parseTree);

        $this->createJsonFiles($parseTree);
        die();
    }

    private function createJsonFiles(array $parseTree): void
    {
        foreach ($parseTree as $executionKey => $execution) {
            file_put_contents($this->outputDir.$execution->getKey().'.json', json_encode($execution, JSON_PRETTY_PRINT));
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
