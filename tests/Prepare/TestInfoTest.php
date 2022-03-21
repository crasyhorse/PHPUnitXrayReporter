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
class TestInfoTest extends TestCase
{
    // ==================================================================================
    // TESTINFO-definition
    // ==================================================================================

    /**
     * XRAY-TESTINFO-definition test 1
     * testing definition annotation. Should take more than one line of the string.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     * But ist this line also shown in the json result?
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-35
     */
    public function testinfo_definition_test_1(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-definition test 2
     * testing definition annotation. The *\/ String should not be shown.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-36
     *
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     * But ist this line also shown in the json result?*/
    public function testinfo_definition_test_2(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    // ==================================================================================
    // TESTINFO-labels
    // ==================================================================================

    /**
     * XRAY-TESTINFO-labels test 2
     * testing labels annotation.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-37
     */
    public function testinfo_labels_test_1(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-labels test 2
     * testing labels annotation. The *\/ String should not be shown.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-38
     *
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage*/
    public function testinfo_labels_test_2(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    // ==================================================================================
    // TESTINFO-projectKey
    // ==================================================================================

    /**
     * More than one projectKey
     * This test uses more than one projectKey space separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-39
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter Irgendwas anderes Interessantes
     */
    public function more_than_one_projectKey_space_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * More than one projectKey
     * This test uses more than one projectKey comma separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-40
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter,Irgendwas,anderes,Interessantes
     */
    public function more_than_one_projectKey_comma_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * No projectKey
     * This test uses no projectKey separated.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-41
     *
     * @XRAY-TESTINFO-projectKey
     */
    public function no_projectKey(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    // ==================================================================================
    // TESTINFO-requirementKeys
    // ==================================================================================

    /**
     * XRAY-TESTINFO-requirementKeys test 1
     * testing defects annotation. Should take more than one line of the string.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2,PHPUnitXrayReporter-49
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-42
     */
    public function testinfo_requirementKeys_test_1(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-requirementKeys test 2
     * testing requirementKeys annotation. The *\/ String should not be shown.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-43
     *
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2,PHPUnitXrayReporter-49*/
    public function testinfo_requirementKeys_test_2(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    // ==================================================================================
    // TESTINFO-testType
    // ==================================================================================

    /**
     * XRAY-TESTINFO-testType 1
     * This test uses more than one testType space separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-44
     *
     * @XRAY-TESTINFO-testType Generic Cumcumber Alcohol_test Personality_test
     */
    public function more_than_one_testType_space_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-testType 2
     * This test uses more than one testType comma separated. Just the first one of them should be parsed.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-45
     *
     * @XRAY-TESTINFO-testType Generic,Cumcumber,Alcohol_test,Personality_test
     */
    public function more_than_one_testType_comma_separated(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-testType 3
     * This test uses no TestType. An Exception should be thrown.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-46
     *
     * @XRAY-TESTINFO-testType
     */
    public function no_testType(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * XRAY-TESTINFO-testType 4
     * This test uses an illegal TestType argument. An Exception should be thrown.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-34
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-47
     *
     * @XRAY-TESTINFO-testType Alcohol_test
     */
    public function illegal_testType(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }
}
