<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Reporter\Results;

use Carbon\Carbon;

/**
 * Represents the result of a successful test run.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class SuccessfulTest extends AbstractTestResult
{
    /**
     * @var string
     */
    public const TEST_RESULT = 'PASS';

    public function __construct(string $test, float $time, Carbon $start)
    {
        parent::__construct($test, $time, $start);
    }

    final public function getStatus(): string
    {
        return self::TEST_RESULT;
    }
}
