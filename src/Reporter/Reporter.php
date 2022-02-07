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
    public function __construct(array $whitelistedTags = [], array $blacklistedTags = [], array $additionalCustomTags = [])
    {
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

        var_dump($parsedResults);
        die();
        $parseTree = $this->parser->groupResults($parsedResults);
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
            $parsed = $this->parser->parse($result);
            $parsedResults[] = $parsed;
        }

        return $parsedResults;
    }
}
