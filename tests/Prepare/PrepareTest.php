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
    /**
     * Successful test.
     *
     * @test
     * @XRAY-testExecutionKey PRTL-666
     */
    public function add_adds_two_integer_values(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Successful test using the testdox annotation to set
     * a human readable test summary.
     *
     * @test
     * @testdox Method add adds two integer values
     *
     * @XRAY-testExecutionKey PRTL-666
     */
    public function add_adds_two_integer_values_using_testdox_annotation(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Successful, data driven test.
     *
     * @test
     * @dataProvider integer_provider
     *
     * @XRAY-testExecutionKey PRTL-666
     */
    public function add_adds_two_integers_from_a_list_of_values(int $a, int $b, int $result): void
    {
        $asset = new Asset();
        $actual = $asset->add($a, $b);
        $this->assertEquals($result, $actual);
    }

    /**
     * Returns an Error because the first operand $a is a string.
     *
     * @test
     * @XRAY-testExecutionKey PRTL-666
     */
    public function test_returning_an_error_because_the_first_operand_is_a_string(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add('a', 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * This test fails because 2 + 2 is not 5.
     *
     * @test
     * @XRAY-testExecutionKey PRTL-666
     */
    public function add_adds_two_integers_but_does_not_return_the_expected_result(): void
    {
        $asset = new Asset();
        $expected = 5;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * This test does nothing because it is skipped.
     *
     * @test
     * @XRAY-testExecutionKey PRTL-666
     */
    public function test_does_nothing_because_it_is_skipped(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    /**
     * This test does nothing because it is incomplete.
     *
     * @test
     * @XRAY-testExecutionKey PRTL-666
     */
    public function test_does_nothing_because_it_is_incomplete(): void
    {
        $this->markTestIncomplete('Incomplete test');
    }

    /**
     * This test is marked as risky.
     *
     * @test
     * @XRAY-testExecutionKey PRTL-666
     */
    public function risky_test(): void
    {
        $this->markAsRisky();
    }

    public function integer_provider(): array
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
}
