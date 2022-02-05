<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter;

use Carbon\Carbon;
use Crasyhorse\PhpunitXrayReporter\Reporter\Reporter;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use DateTimeZone;
use PHPUnit\Runner\AfterIncompleteTestHook;
use PHPUnit\Runner\AfterRiskyTestHook;
use PHPUnit\Runner\AfterSkippedTestHook;
use PHPUnit\Runner\AfterSuccessfulTestHook;
use PHPUnit\Runner\AfterTestErrorHook;
use PHPUnit\Runner\AfterTestFailureHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\AfterTestWarningHook;
use PHPUnit\Runner\BeforeTestHook;

/**
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class Extension implements BeforeTestHook, AfterSuccessfulTestHook, AfterTestFailureHook, AfterSkippedTestHook, AfterIncompleteTestHook, AfterTestWarningHook, AfterTestErrorHook, AfterRiskyTestHook, AfterTestHook
{
    /**
     * @var Carbon
     */
    private $start;

    /**
     * @var Reporter
     */
    private $reporter;

    public function __construct()
    {
        $this->reporter = new Reporter();
    }

    public function __destruct()
    {
        $this->reporter->processResults();
    }

    public function executeBeforeTest(string $test): void
    {
        $this->start = Carbon::now(new DateTimeZone('Europe/Berlin'));
    }

    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        $result = new SuccessfulTest($test, $time, $this->start);
        $this->reporter->add($result);
    }

    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        $result = new FailedTest($test, $time, $this->start, $message);
        $this->reporter->add($result);
    }

    public function executeAfterTestError(string $test, string $message, float $time): void
    {
        $result = new FailedTest($test, $time, $this->start, $message);
        $this->reporter->add($result);
    }

    public function executeAfterTestWarning(string $test, string $message, float $time): void
    {
        $result = new FailedTest($test, $time, $this->start, $message);
        $this->reporter->add($result);
    }

    public function executeAfterSkippedTest(string $test, string $message, float $time): void
    {
    }

    public function executeAfterIncompleteTest(string $test, string $message, float $time): void
    {
    }

    public function executeAfterRiskyTest(string $test, string $message, float $time): void
    {
    }

    public function executeAfterTest(string $test, float $time): void
    {
    }
}
