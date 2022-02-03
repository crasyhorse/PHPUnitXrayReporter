<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * Represents a Xray "TestInfo" object.
 * 
 * @author Florian Weidinger
 */
class TestInfo {

    /**
     * @var string
     */
    private $projectKey;

    /**
     * @var string
     */
    private $testType;

    /**
     * @var string[]
     */
    private $requirementsKeys;

    /**
     * @var string[]
     */
    private $labels;

    /**
     * @var string
     */
    private $definition;

    public function __contruct(string $projectKey, string $testType, array $requirementsKeys, array $labels, string $definition)
    {
        $this->projectKey = $projectKey;
        $this->testType = $testType;
        $this->requirementsKeys = $requirementsKeys;
        $this->labels = $labels;
        $this->definition = $definition;
    }
}