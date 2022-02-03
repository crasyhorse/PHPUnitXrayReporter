<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * Represents a Xray "TestInfo" object.
 * 
 * @author Florian Weidinger
 */
class TestInfo implements Serializable{

    /**
     * @var string
     */
    private $projectKey;

    /**
     * @var string
     */
    private $testType;

    /**
     * @var array<array-key, string>
     */
    private $requirementsKeys;

    /**
     * @var array<array-key, string>
     */
    private $labels;

    /**
     * @var string
     */
    private $definition;

    /**
     * @param array<array-key, string> $requirementsKeys
     * @param array<array-key, string> $labels
     */
    public function __construct(string $projectKey, string $testType, array $requirementsKeys, array $labels, string $definition)
    {
        $this->projectKey = $projectKey;
        $this->testType = $testType;
        $this->requirementsKeys = $requirementsKeys;
        $this->labels = $labels;
        $this->definition = $definition;
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