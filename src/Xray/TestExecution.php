<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * Represents a XRay "Test execution" object.
 * 
 * @author Florian Weidinger
 */
class TestExecution implements Serializable {
    
    /**
     * @var string
     */
    private $key = '';

    /**
     * @var Info
     */
    private $info;

    /**
     * @var Test[]
     */
    private $tests = [];

    public function __construct(string $key, Info $info) {
        $this->key = $key;
        $this->info = $info;
        $this->tests;
    }

    /**
     * Add a test result to the list.
     * 
     * @param Test $test
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