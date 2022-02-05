<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter;

use Crasyhorse\PhpunitXrayReporter\Parser\Parser;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;

/**
 * Processes test results and the meta information for the annotations.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class Reporter
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

    public function add(TestResult $testResult): void
    {
        $this->testResults[] = $testResult;
    }

    public function processResults(): void
    {
        foreach ($this->testResults as $result) {
            $parsed = $this->parser->parse($result);

            var_dump($parsed);
        }
    }
}
