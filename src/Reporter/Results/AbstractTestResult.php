<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter\Results;

use Carbon\Carbon;
use Carbon\CarbonInterval;

/**
 * Represents the result of a successful test run.
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

    public function __construct(string $test, float $time, Carbon $start, string $message = null)
    {
        $this->test = $test;
        $this->time = $time;
        $this->start = $start;
        $this->message = $message;
    }

    public function getFinish(): string
    {
        return $this->start->add(CarbonInterval::milliseconds($this->time))->toFormattedDateString();
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
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
