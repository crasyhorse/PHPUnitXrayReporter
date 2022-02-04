<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter;

use Carbon\Carbon;
use Crasyhorse\PhpunitXrayReporter\Parser\Parser;
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

    private $parser;

    public function __construct()
    {
        $this->start = Carbon::now();
        $this->parser = new Parser();
    }

    public function executeBeforeTest(string $test): void
    {
    }

    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        /** @var class-string $test */
        $result = $this->parser->parse($test);
        var_dump($result);
    }

    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        /** @var class-string $test */
        $result = $this->parser->parse($test);
        var_dump($result);
    }

    public function executeAfterTestError(string $test, string $message, float $time): void
    {
        /** @var class-string $test */
        $result = $this->parser->parse($test);
        var_dump($result);
    }

    public function executeAfterTestWarning(string $test, string $message, float $time): void
    {
        /** @var class-string $test */
        $result = $this->parser->parse($test);
        var_dump($result);
    }

    public function executeAfterSkippedTest(string $test, string $message, float $time): void
    {
        /** @var class-string $test */
        $result = $this->parser->parse($test);
        var_dump($result);
    }

    public function executeAfterIncompleteTest(string $test, string $message, float $time): void
    {
        /** @var class-string $test */
        $result = $this->parser->parse($test);
        var_dump($result);
    }

    public function executeAfterRiskyTest(string $test, string $message, float $time): void
    {
        /** @var class-string $test */
        $result = $this->parser->parse($test);
        var_dump($result);
    }

    public function executeAfterTest(string $test, float $time): void
    {
    }
}
