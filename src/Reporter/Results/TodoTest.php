<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Reporter\Results;

use Carbon\Carbon;

/**
 * Represents the result of a failed test run.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class TodoTest extends AbstractTestResult
{
    /**
     * @var string
     */
    public const TEST_RESULT = 'TODO';

    public function __construct(string $test, float $time, Carbon $start, string $message)
    {
        parent::__construct($test, $time, $start, $message);
    }

    /**
     * @return "PASS" | "FAIL" | "TODO"
     */
    final public function getStatus()
    {
        return self::TEST_RESULT;
    }
}
