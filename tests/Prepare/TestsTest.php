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
class TestsTest extends TestCase
{
    /**
     * More than one TestKey
     * This test uses more than one TestKey space separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-28
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-29 PHPUnitXrayReporter-018 PHPUnitXrayReporter-118
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function more_than_one_TestKey_space_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * More than one TestKey
     * This test uses more than one TestKey comma separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-28
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-30,PHPUnitXrayReporter-018,PHPUnitXrayReporter-118
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function more_than_one_TestKey_comma_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * No TestKey
     * This test uses no TestKey separated. An Exception should be thrown.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-28
     *
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function no_TestKey(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTS-defects test 1
     * testing defects annotation.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-28
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-32
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2,PHPUnitXrayReporter-3,PHPUnitXrayReporter-4
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     */
    public function defects_test_1(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTS-defects test 2
     * testing defects annotation. The *\/ String should not be shown.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-28
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-33
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2,PHPUnitXrayReporter-3,PHPUnitXrayReporter-4*/
    public function defects_test_2(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }
}
