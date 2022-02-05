<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter\Results;

use Carbon\Carbon;

/**
 * Represents the result of a failed test run.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class FailedTest extends AbstractTestResult
{
    /**
     * @var string
     */
    const TEST_RESULT = 'FAIL';

    public function __construct(string $test, float $time, Carbon $start, string $message)
    {
        parent::__construct($test, $time, $start, $message);
    }

    public function getStatus(): string {
        return self::TEST_RESULT;
    }
}
