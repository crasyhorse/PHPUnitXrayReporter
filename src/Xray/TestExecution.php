<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * Represents a XRay "Test execution" object.
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

    /**
     * Add a test result to the list.
     * 
     * @param Crasyhorse\PhpunitXrayReporter\Xray\Test $test
     * 
     * @return void
     */
    public function addTest(Test $test): void
    {
        $this->tests[] = $test;
    }

    /**
     * Serializes the class into a JSON string.
     * 
     * @return string
     */
    public function toJson(): string
    {
        // code
    }
}