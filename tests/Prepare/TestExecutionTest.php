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
class TestExecutionTest extends TestCase
{
    /**
     * More than one TestExecutionKey
     * This test uses more than one TestExecutionKey space separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-24 PHPUnitXrayReporter-013 PHPUnitXrayReporter-113
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-25
     */
    public function more_than_one_TestExecutionKey_space_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * More than one TestExecutionKey
     * This test uses more than one TestExecutionKey comma separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-24,PHPUnitXrayReporter-013,PHPUnitXrayReporter-113
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-26
     */
    public function more_than_one_TestExecutionKey_comma_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /*
     * No TestExecutionKey
     * This test uses no TestExecutionKey. An Exception should be thrown.
     *
     * @test
     * @XRAY-testExecutionKey
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-27
     */
    // public function no_TestExecutionKey(): void
    // {
    //     $asset = new Asset();
    //     $expected = 4;
    //     $actual = $asset->add(2, 2);
    //     $this->assertEquals($expected, $actual);
    // }
}
