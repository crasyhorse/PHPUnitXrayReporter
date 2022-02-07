<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Unit;

use Carbon\Carbon;
use Crasyhorse\PhpunitXrayReporter\Parser\Parser;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
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
                    'XRAY-INFO-user' => 'CalamityCoyote',
                    'XRAY-INFO-testEnvironments' => [
                        'PHP-Unit',
                    ],
                    'XRAY-TESTS-testKey' => 'DEMO-123',
                    'start' => $start->toIso8601String(),
                    'comment' => 'Test has passed.',
                    'status' => SuccessfulTest::TEST_RESULT,
                    'XRAY-INFO-project' => 'DEMO',
                ],
            ],
            'Successful test result without Info object.' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec2', 0.1, $start),
                [
                    'XRAY-testExecutionKey' => 'DEMO-667',
                    'XRAY-TESTS-testKey' => 'DEMO-123',
                    'start' => $start->toIso8601String(),
                    'comment' => 'Test has passed.',
                    'status' => SuccessfulTest::TEST_RESULT,
                ],
            ],
            'Successful test result with Test and TestInfo objects.' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec3', 0.1, $start),
                [
                    'XRAY-testExecutionKey' => 'DEMO-668',
                    'XRAY-TESTS-testKey' => 'DEMO-123',
                    'start' => $start->toIso8601String(),
                    'comment' => 'Test has passed.',
                    'status' => SuccessfulTest::TEST_RESULT,
                    'XRAY-TESTINFO-projectKey' => 'DEMO',
                    'XRAY-TESTINFO-testType' => 'Generic',
                    'XRAY-TESTINFO-requirementKeys' => [
                        'DEMO-1', 'DEMO-2', 'DEMO-3',
                    ],
                    'XRAY-TESTINFO-labels' => [
                        'workInProgress', 'demo',
                    ],
                    'XRAY-TESTINFO-definition' => "Let's test",
                ],
            ],
            'Failed test result including Info object.' => [
                new FailedTest('CrasyHorse\Tests\Assets\PseudoSpec::spec4', 0.1, $start, 'Failed asserting that 4 matches expected 5.'),
                [
                    'XRAY-testExecutionKey' => 'DEMO-669',
                    'XRAY-INFO-summary' => 'Update test execution DEMO-669 including the Info object',
                    'XRAY-INFO-description' => 'This is some kind of a test double for testing the Parser class. That means it is only a pseudo spec.',
                    'XRAY-INFO-version' => '1.0',
                    'XRAY-INFO-revision' => '1.0.1',
                    'XRAY-INFO-user' => 'CalamityCoyote',
                    'XRAY-INFO-testEnvironments' => [
                        'PHP-Unit',
                    ],
                    'XRAY-TESTS-testKey' => 'DEMO-124',
                    'start' => $start->toIso8601String(),
                    'comment' => 'Failed asserting that 4 matches expected 5.',
                    'status' => FailedTest::TEST_RESULT,
                    'XRAY-INFO-project' => 'DEMO',
                ],
            ],
        ];
    }

    /**
     * Looks for the existence of the 'finish' field. If it exists, it will be checked against
     * the 'start' field. Returns true if 'finish' is greater than or equal to 'start'.
     *
     * @return void
     */
    private function assertFinishIsGreaterThanOrEqualStart(array $actual, array $expected): void
    {
        if (array_key_exists('finish', $actual)) {
            $start = Carbon::parse($actual['start'], 'Europe/Berlin');
            $finish = Carbon::parse($actual['finish'], 'Europe/Berlin');
            $this->assertTrue($finish->greaterThanOrEqualTo($start));
        }
    }

    /**
     * Removes the following timestamp field from an array:
     * # finish.
     *
     * @return array
     */
    private function removeTimestamps(array $parsedResult): array
    {
        unset($parsedResult['finish']);

        return $parsedResult;
    }
}
