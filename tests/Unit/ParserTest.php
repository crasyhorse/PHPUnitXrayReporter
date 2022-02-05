<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Unit;

use Carbon\Carbon;
use CrasyHorse\PhpunitXrayReporter\Parser\Parser;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class ParserTest extends TestCase
{
    /**
     * @test
     * @group Parser
     * @dataProvider test_result_provider
     */
    public function parser_parses_doc_block_correctly(TestResult $testResult, array $expected): void
    {
        $parser = new Parser();

        $actual = $parser->parse($testResult);

        $this->assertFinishIsGreaterThanOrEqualStart($actual, $expected);
        
        $actual = $this->removeTimestamps($actual);
        $expected = $this->removeTimestamps($expected);
        $this->assertEquals($expected, $actual);
    }

    public function test_result_provider(): array
    {
        $start = Carbon::now(new DateTimeZone('Europe/Berlin'));

        return [
            'Successful test result including Info object.' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec1', 0.1, $start),
                [
                    'XRAY-testExecutionKey' => 'DEMO-666',
                    'XRAY-INFO-summary' => 'Update test execution DEMO-666 including the Info object',
                    'XRAY-INFO-description' => 'This is some kind of a test double for testing the Parser class. That means it is only a pseudo spec.',
                    'XRAY-INFO-version' => '1.0',
                    'XRAY-INFO-revision' => '1.0.1',
                    'XRAY-INFO-user' => 'Calamity',
                    'XRAY-INFO-testEnvironments' => [
                        'PHP-Unit',
                    ],
                    'start' => $start->toIso8601String(),
                    'finish' => null,
                    'comment' => 'Test has passed.',
                ],
            ],
        ];
    }

    private function assertFinishIsGreaterThanOrEqualStart(array $actual, array $expected): void
    {
        if (array_key_exists('finish', $expected)) {
            $this->assertArrayHasKey('finish', $actual);
            $start = Carbon::parse($actual['start'], 'Europe/Berlin');
            $finish = Carbon::parse($actual['finish'], 'Europe/Berlin');
            $this->assertTrue($finish->greaterThanOrEqualTo($start));
        }
    }

    private function removeTimestamps(array $parsedResult): array
    {
        unset($parsedResult['finish']);

        return $parsedResult;
    }
}
