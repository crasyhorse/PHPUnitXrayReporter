<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter\Results;

use Carbon\Carbon;

/**
 * Represents the result of a test run.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
abstract class AbstractTestResult implements TestResult
{
    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Carbon
     */
    protected $start;

    /**
     * @var string
     */
    protected $test;

    /**
     * @var float
     */
    protected $time;

    public function __construct(string $test, float $time, Carbon $start, string $message = null)
    {
        $this->test = $test;
        $this->name = $this->extractName($test);
        $this->time = $time * 1000; // converting to milliseconds for better usage of carbon
        $this->start = $start;
        $this->message = $message;
    }

    /**
     * Calculates the point in time the test has finished and returns it as a human readable
     * date string.
     *
     * @return string
     */
    public function getFinish(): string
    {
        $finish = $this->start->copy();
        $finish->addMilliseconds($this->time);

        return $finish->toIso8601String();
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStart(): string
    {
        return $this->start->toIso8601String();
    }

    public function getTest(): string
    {
        return $this->test;
    }

    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * Takes hole PHPUnit test case string and extract the method name of the test.
     */
    private function extractName(string $testString): string
    {
        preg_match('/(?<=::)([_0-9a-zA-Z]+)/', $testString, $name);

        return $name[0];
    }
}
