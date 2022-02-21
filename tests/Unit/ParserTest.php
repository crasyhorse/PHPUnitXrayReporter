<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Unit;

use Carbon\Carbon;
use Crasyhorse\PhpunitXrayReporter\Config;
use Crasyhorse\PhpunitXrayReporter\Parser\Parser;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TodoTest;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\InfoBuilder;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestBuilder;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestInfoBuilder;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestExecution;
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
     * @var string
     */
    protected $configDirExceptions;

    /**
     * @var string
     */
    protected $configDirWithInfo;

    /**
     * @var string
     */
    protected $configDirWithoutInfo;

    protected function setup(): void
    {
        $this->configDirExceptions = '.';
        foreach (['tests', 'Assets', 'xray-reporterrc_parsertest_exceptions.json'] as $pathPart) {
            $this->configDirExceptions = $this->configDirExceptions.DIRECTORY_SEPARATOR.$pathPart;
        }

        $this->configDirWithInfo = '.';
        foreach (['tests', 'Assets', 'xray-reporterrc_parsertest_with_info.json'] as $pathPart) {
            $this->configDirWithInfo = $this->configDirWithInfo.DIRECTORY_SEPARATOR.$pathPart;
        }

        $this->configDirWithoutInfo = '.';
        foreach (['tests', 'Assets', 'xray-reporterrc_parsertest_without_info.json'] as $pathPart) {
            $this->configDirWithoutInfo = $this->configDirWithoutInfo.DIRECTORY_SEPARATOR.$pathPart;
        }
    }

    /**
     * @test
     * @group Parser
     * @dataProvider doc_block_correctness_result_provider
     */
    public function parser_parses_doc_block_correctly(TestResult $testResult, array $expected): void
    {
        $parser = new Parser($this->configDirExceptions);
        $actual = $parser->parse($testResult);
        $this->assertFinishIsGreaterThanOrEqualStart($actual, $expected);

        $actual = $this->removeTimestamps($actual);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @group Parser
     * @dataProvider objects_with_info_data_provider
     */
    public function parser_generates_objects_with_info_correctly(TestResult $testResult, TestExecution $expected): void
    {
        $parser = new Parser($this->configDirWithInfo);
        $actual = $parser->parse($testResult);
        $parser->groupResults([$actual]);
        $actual = array_values($parser->getMergedTestExecutionList())[0];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @group Parser
     * @dataProvider objects_without_info_data_provider
     */
    public function parser_generates_objects_without_info_correctly(TestResult $testResult, TestExecution $expected): void
    {
        $parser = new Parser($this->configDirWithoutInfo);
        $actual = $parser->parse($testResult);
        $parser->groupResults([$actual]);
        $actual = array_values($parser->getMergedTestExecutionList())[0];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @group Parser
     * @dataProvider Parsed_test_result_provider_for_errors
     */
    public function parser_throw_exception_if_testKey_or_testExecutionKey_tag_given_without_value(TestResult $testResult): void
    {
        $parser = new Parser($this->configDirExceptions);
        $this->expectException(InvalidArgumentException::class);
        $parsedResults = $parser->parse($testResult);
        $result = $parser->groupResults([$parsedResults]);
    }

    /**
     * @test
     * @group Parser
     */
    public function config_throws_exception_if_given_path_is_false(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $config = new Config($this->configDirExceptions.'.strange_prefix');
    }

    public function doc_block_correctness_result_provider(): array
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
            'Successful test result with little information' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec2', $time, $start),
                [
                    'XRAY-testExecutionKey' => 'DEMO-667',
                    'XRAY-TESTS-testKey' => 'DEMO-123',
                    'summery' => 'Update test execution DEMO-667 with little information.',
                    'description' => 'Update test execution DEMO-667 with little information.',
                    'start' => $start->toIso8601String(),
                    'status' => SuccessfulTest::TEST_RESULT,
                    'name' => 'spec2',
                    'comment' => 'Test has passed.',
                ],
            ],
            'Failed test result without testExecutionKey and summary' => [
                new FailedTest('CrasyHorse\Tests\Assets\PseudoSpec::spec3', $time, $start, 'Failed asserting 2+4=6'),
                [
                    'XRAY-TESTS-testKey' => 'DEMO-123',
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
                    'XRAY-TESTINFO-definition' => 'Let\'s test',
                    'start' => $start->toIso8601String(),
                    'status' => FailedTest::TEST_RESULT,
                    'name' => 'spec3',
                    'comment' => 'Failed asserting 2+4=6',
                ],
            ],
        ];
    }

    public function Parsed_test_result_provider_for_errors()
    {
        $start = Carbon::now(new DateTimeZone('Europe/Berlin'));
        // TODO time are Milliseconds or seconds? This Test acted before like Seconds, but implementation in TestResults like milliseconds
        $time = 2000;

        return [
            'XRAY-testExecutionKey is missing' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec5', $time, $start),
            ],
            'XRAY-TESTS-testKey is missing' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec6', $time, $start),
            ],
            'XRAY-TESTINFO-projectKey is missing' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec7', $time, $start),
            ],
        ];
    }

    public function objects_with_info_data_provider()
    {
        $start = Carbon::now(new DateTimeZone('Europe/Berlin'));
        // TODO time are Milliseconds or seconds? This Test acted before like Seconds, but implementation in TestResults like milliseconds
        $time = 0;

        $info = (new InfoBuilder())
            ->setProject('DEMO')
            ->setSummary('example-config')
            ->setDescription('This is an example for description')
            ->setVersion('0.3.0')
            ->setRevision('0.3.0')
            ->setUser('Botuser')
            ->setTestEnvironments(['IOS', 'Android'])
            ->build();

        $testExecution1 = new TestExecution('DEMO-666');
        $testExecution1->addTest(
            (new TestBuilder())
                ->setName('spec1')
                ->setStart($start->toIso8601String())
                ->setFinish($start->toIso8601String())
                ->setComment('Failed asserting 2+4=6.')
                ->setStatus('FAIL')
                ->setTestKey('DEMO-123')
                ->setDefects(['DEMO-1', 'DEMO-2'])
                ->setTestInfo(
                    (new TestInfoBuilder())
                        ->setProjectKey('DEMO')
                        ->setTestType('Generic')
                        ->setRequirementKeys(['DEMO-1', 'DEMO-2'])
                        ->setLabels(['workInProgress', 'Bug', 'NeedsTriage'])
                        ->setDefinition('The Test does nothing')
                        ->setSummary('Update test execution DEMO-666.')
                        ->setDescription("Update test execution DEMO-666.\nThis test will return a PASS result and has all possible annotations we implemented.")
                        ->build()
                )
                ->build()
        );

        $testExecution2 = new TestExecution('DEMO-667');
        $testExecution2->addTest(
            (new TestBuilder())
                ->setName('spec2')
                ->setStart($start->toIso8601String())
                ->setFinish($start->toIso8601String())
                ->setComment('Failed asserting 2+4=6.')
                ->setStatus('TODO')
                ->setTestKey('DEMO-123')
                ->setTestInfo(
                    (new TestInfoBuilder())
                        ->setProjectKey('DEMO')
                        ->setTestType('Generic')
                        ->setSummary('Update test execution DEMO-667 with little information.')
                        ->setDescription('Update test execution DEMO-667 with little information.')
                        ->setDefinition('spec2')
                        ->build()
                )
                ->build()
        );

        $testExecution3 = new TestExecution();
        $testExecution3->addInfo($info);
        $testExecution3->addTest(
            (new TestBuilder())
                ->setName('spec3')
                ->setStart($start->toIso8601String())
                ->setFinish($start->toIso8601String())
                ->setComment('Test has passed.')
                ->setStatus('PASS')
                ->setTestKey('DEMO-123')
                ->setDefects(['DEMO-1', 'DEMO-2'])
                ->setTestInfo(
                    (new TestInfoBuilder())
                        ->setProjectKey('DEMO')
                        ->setTestType('Generic')
                        ->setRequirementKeys(['DEMO-1', 'DEMO-2', 'DEMO-3'])
                        ->setLabels(['workInProgress', 'demo'])
                        ->setDefinition('Let\'s test')
                        ->setSummary('spec3')
                        ->build()
                )
                ->build()
        );

        return [
            'FailedTest and testExecutionKey given as annotation so info not needed' => [
                new FailedTest('CrasyHorse\Tests\Assets\PseudoSpec::spec1', $time, $start, 'Failed asserting 2+4=6.'),
                $testExecution1,
            ],
            'TodoTest and very little information in doc block but testExecutionKey given' => [
                new TodoTest('CrasyHorse\Tests\Assets\PseudoSpec::spec2', $time, $start, 'Failed asserting 2+4=6.'),
                $testExecution2,
            ],
            'SuccessfulTest and without testExecutionKey as annotation so info needed' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec3', $time, $start),
                $testExecution3,
            ],
        ];
    }

    public function objects_without_info_data_provider()
    {
        $start = Carbon::now(new DateTimeZone('Europe/Berlin'));
        // TODO time are Milliseconds or seconds? This Test acted before like Seconds, but implementation in TestResults like milliseconds
        $time = 0;

        $testExecution1 = new TestExecution('DEMO-666');
        $testExecution1->addTest(
            (new TestBuilder())
                ->setName('spec1')
                ->setStart($start->toIso8601String())
                ->setFinish($start->toIso8601String())
                ->setComment('Failed asserting 2+4=6.')
                ->setStatus('FAIL')
                ->setTestKey('DEMO-123')
                ->setDefects(['DEMO-1', 'DEMO-2'])
                ->setTestInfo(
                    (new TestInfoBuilder())
                        ->setProjectKey('DEMO')
                        ->setTestType('Generic')
                        ->setRequirementKeys(['DEMO-1', 'DEMO-2'])
                        ->setLabels(['workInProgress', 'Bug', 'NeedsTriage'])
                        ->setDefinition('The Test does nothing')
                        ->setSummary('Update test execution DEMO-666.')
                        ->setDescription("Update test execution DEMO-666.\nThis test will return a PASS result and has all possible annotations we implemented.")
                        ->build()
                )
                ->build()
        );

        $testExecution2 = new TestExecution('DEMO-667');
        $testExecution2->addTest(
            (new TestBuilder())
                ->setName('spec2')
                ->setStart($start->toIso8601String())
                ->setFinish($start->toIso8601String())
                ->setComment('Failed asserting 2+4=6.')
                ->setStatus('TODO')
                ->setTestKey('DEMO-123')
                ->setTestInfo(
                    (new TestInfoBuilder())
                        ->setProjectKey('DEMO')
                        ->setTestType('Generic')
                        ->setSummary('Update test execution DEMO-667 with little information.')
                        ->setDescription('Update test execution DEMO-667 with little information.')
                        ->setDefinition('spec2')
                        ->build()
                )
                ->build()
        );

        $testExecution3 = new TestExecution('DEMO-690');
        $testExecution3->addTest(
            (new TestBuilder())
                ->setName('spec3')
                ->setStart($start->toIso8601String())
                ->setFinish($start->toIso8601String())
                ->setComment('Test has passed.')
                ->setStatus('PASS')
                ->setTestKey('DEMO-123')
                ->setDefects(['DEMO-1', 'DEMO-2'])
                ->setTestInfo(
                    (new TestInfoBuilder())
                        ->setProjectKey('DEMO')
                        ->setTestType('Generic')
                        ->setRequirementKeys(['DEMO-1', 'DEMO-2', 'DEMO-3'])
                        ->setLabels(['workInProgress', 'demo'])
                        ->setDefinition('Let\'s test')
                        ->setSummary('spec3')
                        ->build()
                )
                ->build()
        );

        return [
            'FailedTest and testExecutionKey given as annotation' => [
                new FailedTest('CrasyHorse\Tests\Assets\PseudoSpec::spec1', $time, $start, 'Failed asserting 2+4=6.'),
                $testExecution1,
            ],
            'TodoTest and very little information in doc block but testExecutionKey given' => [
                new TodoTest('CrasyHorse\Tests\Assets\PseudoSpec::spec2', $time, $start, 'Failed asserting 2+4=6.'),
                $testExecution2,
            ],
            'SuccessfulTest and without testExecutionKey as annotation so testExecutionKey from config needed' => [
                new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec3', $time, $start),
                $testExecution3,
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
