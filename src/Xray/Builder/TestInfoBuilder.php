<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Builder;

use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\XrayType;

class TestInfoBuilder implements Builder
{
    /**
     * @var string
     */
    private $summary = '';

    /**
     * @var string
     */
    private $description = '';

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

    public function __construct()
    {
        // Intentionally left blank
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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
    public function build(): TestInfo
    {
        return new TestInfo($this);
    }
}
