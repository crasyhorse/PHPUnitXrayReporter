<?php

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
    private $requirementsKeys = [];

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
    public function getRequirementsKeys()
    {
        return $this->requirementsKeys;
    }
    
    /**
     * @param array<int, string> $requirementsKeys
     */
    public function setRequirementsKeys($requirementsKeys): self
    {
        $this->requirementsKeys = $requirementsKeys;
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