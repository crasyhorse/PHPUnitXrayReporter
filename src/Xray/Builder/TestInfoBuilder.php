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
     * @var string
     */
    private $testType = '';

    /**
     * @var array<int, string>
     */
    private $requirementKeys = [];

    /**
     * @var array<int, string>
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

    public function getTestType(): string
    {
        return $this->testType;
    }
    
    public function setTestType(string $testType): self
    {
        $this->testType = $testType;
        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getRequirementKeys()
    {
        return $this->requirementKeys;
    }
    
    /**
     * @param array<int, string> $requirementKeys
     */
    public function setRequirementKeys($requirementKeys): self
    {
        $this->requirementKeys = $requirementKeys;
        return $this;
    }

    /**
     * @return array<int,string>
     */
    public function getLabels()
    {
        return $this->labels;
    }
    
    /**
     * @param array<int,string> $labels
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
     * @return XrayType
     */
    public function build(): XrayType {
        return new TestInfo($this);
    }

}