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
 * @group Prepare
 *
 * @since 0.1.0
 */
class XRAYUploadTest extends TestCase
{
    // ==================================================================================
    // All annotations tests
    // ==================================================================================

    /**
     * XRAY Test.
     * This test will return a PASS result and has all possible annotations we implemented.
     *
     * @test
     * @XRAY-TESTS-testKey PRTL-5635
     * @XRAY-TESTS-comment This Test should return PASS
     * and this is second line
     *
     * @XRAY-TESTINFO-projectKey PRTL
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function fully_annotated_successful_test_for_XRAY(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /*
     * Unsuccessful test.
     * This test will return a FAIL result and has all possible annotations we've implemented.
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
    // public function fully_annotated_unsuccessful_test(): void
    // {
    //     $asset = new Asset();
    //     $expected = 5;
    //     $actual = $asset->add(2, 2);
    //     $this->assertEquals($expected, $actual);
    // }
}
