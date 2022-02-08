<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Builder;

use Crasyhorse\PhpunitXrayReporter\Xray\Types\XrayType;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;

class TestInfoBuilder implements Builder {

    /**
     * @var string
     */
    private $projectKey = '';

    /**
     * @var "Generic" | "Cumcumber" | null
     */
    private $testType = 'Generic';

    /**
     * @var array<array-key, string>
     */
    private $requirementKeys = [];

    /**
     * @var array<array-key, string>
     */
    private $labels = [];

    /**
     * @var string
     */
    private $definition = '';

    
    public function __construct(){
        // Intentionally left blank
    }

    public function getProjectKey(): string
    {
        return $this->projectKey;
    }

    public function setProjectKey(string $projectKey): self
    {
        $this->projectKey = $projectKey;
        return $this;
    }

    /**
     * @return "Generic" | "Cumcumber" | null
     */
    public function getTestType()
    {
        return $this->testType;
    }
    
    /**
     * @param "Generic" | "Cumcumber" | null $testType
     */
    public function setTestType($testType): self
    {
        $this->testType = $testType;
        return $this;
    }

    /**
     * @return array<array-key, string>
     */
    public function getRequirementKeys()
    {
        return $this->requirementKeys;
    }
    
    /**
     * @param array<array-key, string> $requirementKeys
     */
    public function setRequirementKeys($requirementKeys): self
    {
        $this->requirementKeys = $requirementKeys;
        return $this;
    }

    /**
     * @return array<array-key,string>
     */
    public function getLabels()
    {
        return $this->labels;
    }
    
    /**
     * @param array<array-key,string> $labels
     */
    public function setLabels($labels): self
    {
        $this->labels = $labels;
        return $this;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }
    
    public function setDefinition(string $definition): self
    {
        $this->definition = $definition;
        return $this;
    }


    /**
     * Builds a class of type XrayType.
     * 
     * @return TestInfo
     */
    public function build(): TestInfo {
        return new TestInfo($this);
    }

}