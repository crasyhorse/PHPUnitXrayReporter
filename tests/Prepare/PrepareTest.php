<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Prepare;

use CrasyHorse\Tests\Assets\Asset;
use PHPUnit\Framework\TestCase;

/**
 * Executes some PHPUnit specs to generate Xray-JSON result files.
 *
 * The tests included in this class are for preparation purposes only.
 * The "Unit" test suite uses the generated result files to test the
 * extension.
 *
 * Please use the configured Composer scripts "test:all", "test:prepare",
 * "test:unit" and "test:unit-f" to execute the test.
 *
 * @example
 * # Run all specs
 * composer run test:all
 * @example
 * # Run only prepartion test suite "Prepare"
 * composre run test:prepare
 * @example
 * # Run only unit tests in test suite "Unit"
 * composer run test:unit
 * @example
 * # Run a filtered list of test from test suite "Unit"
 * composer run test:unit-f <filter-string>
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class PrepareTest extends TestCase
{
    // ==================================================================================
    // All annotations tests
    // ==================================================================================

    /**
     * Successful test.
     * This test will return a PASS result and has all possible annotations we implemented.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-1
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-2
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function fully_annotated_successful_test(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Unsuccessful test.
     * This test will return a FAIL result and has all possible annotations we implemented.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-1
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-3
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-2,PHPUnitXrayReporter-3
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2,PHPUnitXrayReporter-3
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 but expects 5
     */
    public function fully_annotated_unsuccessful_test(): void
    {
        $asset = new Asset();
        $expected = 5;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    // ==================================================================================
    // Very few annotations tests
    // ==================================================================================

    /**
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-4
     */
    public function only_XRAY_testExecutionKey_annotation(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-4
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-6
     */
    public function XRAY_testExecutionKey_and_XRAY_TESTS_testKey_annotation(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    // ==================================================================================
    // Iteration tests
    // ==================================================================================

    /**
     * @test
     * @dataProvider two_iteration_data_provider1
     *
     * @XRAY-testExecutionKey PHPUnitXrayReporter-7
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-8
     */
    public function test_with_two_iterations_PASS_PASS(int $a, int $b, int $result): void
    {
        $asset = new Asset();
        $actual = $asset->add($a, $b);
        $this->assertEquals($result, $actual);
    }

    /**
     * @test
     * @dataProvider two_iteration_data_provider2
     *
     * @XRAY-testExecutionKey PHPUnitXrayReporter-7
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-9
     */
    public function test_with_two_iterations_FAIL_PASS(int $a, int $b, int $result): void
    {
        $asset = new Asset();
        $actual = $asset->add($a, $b);
        $this->assertEquals($result, $actual);
    }

    /**
     * @test
     * @dataProvider two_iteration_data_provider3
     *
     * @XRAY-testExecutionKey PHPUnitXrayReporter-7
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-10
     */
    public function test_with_two_iterations_PASS_FAIL(int $a, int $b, int $result): void
    {
        $asset = new Asset();
        $actual = $asset->add($a, $b);
        $this->assertEquals($result, $actual);
    }

    /**
     * @test
     * @dataProvider three_iteration_data_provider
     *
     * @XRAY-testExecutionKey PHPUnitXrayReporter-7
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-11
     */
    public function test_with_three_iterations_PASS_FAIL_PASS(int $a, int $b, int $result): void
    {
        $asset = new Asset();
        $actual = $asset->add($a, $b);
        $this->assertEquals($result, $actual);
    }

    // ==================================================================================
    // Different test behavior tests (error, skipped, incomplete, risky)
    // ==================================================================================

    /**
     * Returns an Error because the first operand $a is a string.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-12
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-13
     */
    public function error_test(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add('a', 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * This test does nothing because it is skipped.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-12
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-14
     */
    public function skipped_test(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    /**
     * This test does nothing because it is incomplete.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-12
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-15
     */
    public function incomplete_test(): void
    {
        $this->markTestIncomplete('Incomplete test');
    }

    /**
     * This test is marked as risky.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-12
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-16
     */
    public function risky_test(): void
    {
        $this->markAsRisky();
    }

    // ==================================================================================
    // XRAY-TESTINFO-XXX Tag Tests
    // ==================================================================================

    /**
     * XRAY-TESTINFO-XXX Tag Test
     * This test Execution will test all possible annotations of Testinfo we've implemented.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-17
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-18
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function different_XRAY_TESTINFO_tags_all(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-17
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-19
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     */
    public function different_XRAY_TESTINFO_tags_1(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-XXX Tag Test.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-17
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-20
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     */
    public function different_XRAY_TESTINFO_tags_2(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-XXX Tag Test.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-17
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-21
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     */
    public function different_XRAY_TESTINFO_tags_3(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-XXX Tag Test.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-17
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-22
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function different_XRAY_TESTINFO_tags_4(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-XXX Tag Test
     * This test Execution will test all possible annotations of Testinfo we've implemented.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-17
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-23
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     */
    public function different_XRAY_TESTINFO_tags_5(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    // ==================================================================================
    // DATA PROVIDER
    // ==================================================================================

    public function two_iteration_data_provider1(): array
    {
        return [
            'a = 2, b = 2, result = 4' => [
                2, 2, 4,
            ],
            'a = 3, b = 6, result = 9' => [
                3, 6, 9,
            ],
        ];
    }

    public function two_iteration_data_provider2(): array
    {
        return [
            'a = 2, b = 2, result = 1' => [
                2, 2, 1,
            ],
            'a = 3, b = 6, result = 9' => [
                3, 6, 9,
            ],
        ];
    }

    public function two_iteration_data_provider3(): array
    {
        return [
            'a = 2, b = 2, result = 4' => [
                2, 2, 4,
            ],
            'a = 3, b = 6, result = 27' => [
                3, 6, 27,
            ],
        ];
    }

    public function three_iteration_data_provider(): array
    {
        return [
            'a = 2, b = 2, result = 4' => [
                2, 2, 4,
            ],
            'a = 7, b = 4, result = 10' => [
                7, 4, 10,
            ],
            'a = 3, b = 6, result = 9' => [
                3, 6, 9,
            ],
        ];
    }
}
