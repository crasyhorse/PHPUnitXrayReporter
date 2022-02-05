<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter\Results;

/**
 * Represents the result of a successful test run.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class SuccessfulTest implements TestResult
{
    /**
     * @var "PASS"
     */
    const TEST_RESULT = 'PASS';

    /**
     * @var string
     */
    private $test;

    /**
     * @var float
     */
    private $time;

    public function __construct(string $test, float $time)
    {
        $this->test = $test;
        $this->time = $time;
    }
}
