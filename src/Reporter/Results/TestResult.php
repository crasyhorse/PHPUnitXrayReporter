<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Reporter\Results;

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

    public function getName(): string;

    public function getStart(): string;

    /**
     * @return "PASS" | "FAIL" | "TODO"
     */
    public function getStatus();

    public function getTest(): string;

    public function getTime(): float;
}
