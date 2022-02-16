<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Unit;

use Carbon\Carbon;
use Crasyhorse\PhpunitXrayReporter\Parser\Parser;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use DateTimeZone;
use InvalidArgumentException;
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

    /**
     * @test
     * @group Parser
     * @dataProvider Parsed_test_result_provider_for_errors
     */
    public function parser_throw_exception_if_testKey_and_testExecutionKey_tag_given_without_value(array $testResult): void
    {
        $parser = new Parser();
        $this->expectException(InvalidArgumentException::class);
        $result = $parser->groupResults($testResult);
    }

    public function test_result_provider(): array
    {
        $start = Carbon::now(new DateTimeZone('Europe/Berlin'));
        // TODO time are Milliseconds or seconds? This Test acted before like Seconds, but implementation in TestResults like milliseconds
        $time = 2000;

        return [
            'Successful test result including TestInfo object.' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec1', $time, $start),
                [
                    'XRAY-testExecutionKey' => 'DEMO-666',
                    'XRAY-TESTS-testKey' => 'DEMO-123',
                    'XRAY-TESTS-comment' => 'This Test should return PASS',
                    'XRAY-TESTS-defects' => [
                        'DEMO-1', 'DEMO-2',
                    ],
                    'XRAY-TESTINFO-projectKey' => 'DEMO',
                    'XRAY-TESTINFO-testType' => 'Generic',
                    'XRAY-TESTINFO-requirementKeys' => [
                        'DEMO-1', 'DEMO-2',
                    ],
                    'XRAY-TESTINFO-labels' => [
                        'workInProgress', 'Bug', 'NeedsTriage',
                    ],
                    'XRAY-TESTINFO-definition' => 'The Test does nothing',
                    'summery' => 'Update test execution DEMO-666.',
                    'description' => "Update test execution DEMO-666.\nThis test will return a PASS result and has all possible annotations we implemented.",
                    'start' => $start->toIso8601String(),
                    'status' => SuccessfulTest::TEST_RESULT,
                    'name' => 'spec1',
                    'comment' => 'Test has passed.',
                ],
            ],
            'Successful test result without Info object.' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec2', $time, $start),
                [
                    'XRAY-testExecutionKey' => 'DEMO-667',
                    'XRAY-TESTS-testKey' => 'DEMO-123',
                    'summery' => 'Update test execution DEMO-667 with little information.',
                    'description' => 'Update test execution DEMO-667 with little information.',
                    'start' => $start->toIso8601String(),
                    'status' => SuccessfulTest::TEST_RESULT,
                    'comment' => 'Test has passed.',
                    'name' => 'spec2',
                ],
            ],
            'Successful test result with Test and TestInfo objects.' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec3', $time, $start),
                [
                    'XRAY-testExecutionKey' => 'DEMO-668',
                    'XRAY-TESTS-testKey' => 'DEMO-123',
                    'XRAY-TESTS-comment' => 'This Test should return PASS',
                    'XRAY-TESTS-defects' => [
                        'DEMO-1', 'DEMO-2',
                    ],
                    'XRAY-TESTINFO-projectKey' => 'DEMO',
                    'XRAY-TESTINFO-testType' => 'Generic',
                    'XRAY-TESTINFO-requirementKeys' => [
                        'DEMO-1', 'DEMO-2', 'DEMO-3',
                    ],
                    'XRAY-TESTINFO-labels' => [
                        'workInProgress', 'demo',
                    ],
                    'XRAY-TESTINFO-definition' => "Let's test",
                    'summery' => 'Successful test result with Test and TestInfo objects.',
                    'description' => 'Successful test result with Test and TestInfo objects.',
                    'start' => $start->toIso8601String(),
                    'status' => SuccessfulTest::TEST_RESULT,
                    'comment' => 'Test has passed.',
                    'name' => 'spec3',
                ],
            ],
            'Failed test result including Info object.' => [
                new FailedTest('CrasyHorse\Tests\Assets\PseudoSpec::spec4', 0.1, $start, 'Failed asserting that 4 matches expected 5.'),
                [
                    'XRAY-testExecutionKey' => 'DEMO-669',
                    'XRAY-TESTS-testKey' => 'DEMO-124',
                    'XRAY-TESTS-comment' => 'This Test should return FAIL',
                    'XRAY-TESTS-defects' => [
                        'DEMO-1', 'DEMO-2',
                    ],
                    'XRAY-TESTINFO-projectKey' => 'DEMO',
                    'XRAY-TESTINFO-testType' => 'Generic',
                    'XRAY-TESTINFO-requirementKeys' => [
                        'DEMO-1', 'DEMO-2',
                    ],
                    'XRAY-TESTINFO-labels' => [
                        'workInProgress', 'Bug', 'NeedsTriage',
                    ],
                    'XRAY-TESTINFO-definition' => 'The Test does nothing',
                    'summery' => 'Update test execution DEMO-669.',
                    'description' => "Update test execution DEMO-669.\nThis is really cool!",
                    'start' => $start->toIso8601String(),
                    'status' => FailedTest::TEST_RESULT,
                    'name' => 'spec4',
                    'comment' => 'Failed asserting that 4 matches expected 5.',
                ],
            ],
        ];
    }

    public function Parsed_test_result_provider_for_errors()
    {
        return [
            [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec5', $time, $start),
            ],
            [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec6', $time, $start),
            ],
            [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec7', $time, $start),
            ],
//             'XRAY-testExecutionKey is missing' => [[[
//                 'XRAY-testExecutionKey' => '',
//                 'XRAY-TESTS-testKey' => 'PHPUnitXrayReporter-2',
//                 'XRAY-TESTS-comment' => 'This Test should return PASS',
//                 'XRAY-TESTS-defects' => [
//                     'PHPUnitXrayReporter-1', 'PHPUnitXrayReporter-2',
//                 ],
//                 'XRAY-TESTINFO-projectKey' => 'PHPUnitXrayReporter',
//                 'XRAY-TESTINFO-testType' => 'Generic',
//                 'XRAY-TESTINFO-requirementKeys' => [
//                     'PHPUnitXrayReporter-1', 'PHPUnitXrayReporter-2',
//                 ],
//                 'XRAY-TESTINFO-labels' => [
//                     'workInProgress', 'Bug', 'NeedsTriage',
//                 ],
//                 'XRAY-TESTINFO-definition' => 'The Test sums 2+2=4 and expects 4',
//                 'summery' => 'Successful test.',
//                 'description' => 'Successful test.
// This test will return a PASS result and has all possible annotations we implemented.',
//                 'start' => '2022-02-15T07:36:34+01:00',
//                 'finish' => '2022-02-15T07:36:34+01:00',
//                 'comment' => 'Test has passed.',
//                 'status' => 'PASS',
//                 'name' => 'fully_annotated_successful_test',
//             ]]],
//             'XRAY-TESTS-testKey is missing' => [[[
//                 'XRAY-testExecutionKey' => 'PHPUnitXrayReporter-1',
//                 'XRAY-TESTS-testKey' => '',
//                 'XRAY-TESTS-comment' => 'This Test should return PASS',
//                 'XRAY-TESTS-defects' => [
//                     'PHPUnitXrayReporter-2', 'PHPUnitXrayReporter-3',
//                 ],
//                 'XRAY-TESTINFO-projectKey' => 'PHPUnitXrayReporter',
//                 'XRAY-TESTINFO-testType' => 'Generic',
//                 'XRAY-TESTINFO-requirementKeys' => [
//                     'PHPUnitXrayReporter-1', 'PHPUnitXrayReporter-2', 'PHPUnitXrayReporter-3',
//                 ],
//                 'XRAY-TESTINFO-labels' => [
//                     'workInProgress', 'Bug', 'NeedsTriage',
//                 ],
//                 'XRAY-TESTINFO-definition' => 'The Test sums 2+2=4 but expects 5',
//                 'summery' => 'Unsuccessful test.',
//                 'description' => "Unsuccessful test.
// This test will return a FAIL result and has all possible annotations we've implemented.",
//                 'start' => '2022-02-15T07:36:34+01:00',
//                 'finish' => '2022-02-15T07:36:34+01:00',
//                 'comment' => 'Failed asserting that 4 matches expected 5.',
//                 'status' => 'FAIL',
//                 'name' => 'fully_annotated_unsuccessful_test',
//             ]]],
//             'XRAY-TESTINFO-projectKey and test(Execution)Key is missing' => [[[
//                 'XRAY-TESTS-comment' => 'This Test should return PASS',
//                 'XRAY-TESTS-defects' => [
//                     'PHPUnitXrayReporter-2', 'PHPUnitXrayReporter-3',
//                 ],
//                 'XRAY-TESTINFO-testType' => 'Generic',
//                 'XRAY-TESTINFO-requirementKeys' => [
//                     'PHPUnitXrayReporter-1', 'PHPUnitXrayReporter-2', 'PHPUnitXrayReporter-3',
//                 ],
//                 'XRAY-TESTINFO-labels' => [
//                     'workInProgress', 'Bug', 'NeedsTriage',
//                 ],
//                 'XRAY-TESTINFO-definition' => 'The Test sums 2+2=4 but expects 5',
//                 'summery' => 'Unsuccessful test.',
//                 'description' => "Unsuccessful test.
// This test will return a FAIL result and has all possible annotations we've implemented.",
//                 'start' => '2022-02-15T07:36:34+01:00',
//                 'finish' => '2022-02-15T07:36:34+01:00',
//                 'comment' => 'Failed asserting that 4 matches expected 5.',
//                 'status' => 'FAIL',
//                 'name' => 'fully_annotated_unsuccessful_test',
//             ]]],
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
