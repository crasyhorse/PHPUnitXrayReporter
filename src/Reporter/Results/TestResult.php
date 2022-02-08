<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter\Results;

/**
 * Identifies a class that represents a test result.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
interface TestResult
{

    public function getFinish(): string;

    /**
     * @return string|null
     */
    public function getMessage();

    public function getStart(): string;

    /**
     * @return "PASS" | "FAIL"
     */
    public function getStatus();

    public function getTest(): string;

    public function getTime(): float;
}
