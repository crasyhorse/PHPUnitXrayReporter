<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter\Results;

use Carbon\Carbon;
use Carbon\CarbonInterval;

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

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var string
     */
    protected $name;

    public function __construct(string $test, float $time, Carbon $start, string $message = null)
    {
        $this->test = $test;
        $this->name = $this->extractName($test);
        $this->time = $time;
        $this->start = $start;
        $this->message = $message;
    }

    private function extractName(string $testString): string
    {
        preg_match('/(?<=::)([_0-9a-zA-Z]+)/', $testString, $name);

        return $name[0];
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

        return $finish->add(CarbonInterval::milliseconds($this->time))->toIso8601String();
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
}
