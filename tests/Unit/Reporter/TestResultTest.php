<?php

namespace CrasyHorse\Tests\Unit\Reporter;

use Carbon\Carbon;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\TodoTest;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * @author Florian Weidinger
 *
 * @since 0.1.0
 *
 * @covers CrasyHorse\PhpunitXrayReporter\Reporter\Results\AbstractTestResult
 * @covers CrasyHorse\PhpunitXrayReporter\Reporter\Results\FailedTest
 * @covers CrasyHorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest
 * @covers CrasyHorse\PhpunitXrayReporter\Reporter\Results\TodoTest
 */
class TestResultTest extends TestCase
{
    /**
     * @var Carbon\Carbon
     */
    private $start;

    /**
     * @var float
     */
    private $time;

    /**
     * @var string
     */
    private $test;

    /**
     * @var SuccessfulTest
     */
    private $successfulTest;

    public function setup(): void
    {
        parent::setup();
        $this->start = Carbon::parse('2022-05-13T19:57:10+02:00');
        $this->time = 0.000516427;
        $this->test = 'CrasyHorse\Tests\Prepare\PrepareTest::different_XRAY_TESTINFO_tags_1';

        $this->successfulTest = new SuccessfulTest($this->test, $this->time, $this->start);
    }

    /**
     * @test
     */
    public function successfulTest_getStatus_returns_pass()
    {
        $expected = 'PASS';
        $actual = $this->successfulTest->getStatus();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getFinish_returns_the_correct_timestamp_in_iso_8601_format(): void
    {
        $expected = $this->start->addMilliseconds(intval(round($this->time)))->getPreciseTimestamp(3);
        $actual = Carbon::parse($this->successfulTest->getFinish())->getPreciseTimestamp(3);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function successfulTest_getMessage_returns_null()
    {
        $actual = $this->successfulTest->getMessage();

        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function getName_returns_the_name_of_the_test_method()
    {
        $expected = 'different_XRAY_TESTINFO_tags_1';
        $actual = $this->successfulTest->getName();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getStart_returns_start_as_iso_8601_timestamp()
    {
        $expected = '2022-05-13T19:57:10+02:00';
        $actual = $this->successfulTest->getStart();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getTest_returns_the_fqsen_of_the_test()
    {
        $expected = 'CrasyHorse\Tests\Prepare\PrepareTest::different_XRAY_TESTINFO_tags_1';
        $actual = $this->successfulTest->getTest();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getTime_returns_the_duration_of_the_test_in_milliseconds()
    {
        $expected = 0.000516427 * 1000;
        $actual = $this->successfulTest->getTime();

        $this->assertEquals($expected, $actual);
    }
}
