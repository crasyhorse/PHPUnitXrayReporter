<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Reporter;

/**
 * Processes test results and the meta information for the annotations.
 * 
 * @author Florian Weidinger
 * 
 * @since 0.1.0
 */
class Reporter {

    /**
     * @param string $test Test result from PHP-Unit
     * @param string $message Error message if $test is FAIL, ERROR, WARNING or RISKY
     * @param float $time Duration of the test
     * @param array<int, string> $meta Information evaluated from the annotations
     */
    public function __construct(string $test, string $message = null, float $time, array $meta)
    {
        # code...
    }

}