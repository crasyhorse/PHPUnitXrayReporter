<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * 
 * 
 * @author Florian Weidinger
 */
class TestExecution {
    
    /**
     * @var string
     */
    private $key;

    /**
     * @var Crasyhorse\PhpunitXrayReporter\Xray\Info
     */
    private $info;

    /**
     * @var Crasyhorse\PhpunitXrayReporter\Xray\Test[]
     */
    private $tests;

    public function __construct(string $key, Info $info) {
        $this->key = $key;
        $this->info = $info;
    }

    public function addTest(Test $test): void
    {
        $this->tests[] = $test;
    }

    public function toJson(): string
    {
        // code
    }
}