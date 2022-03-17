<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Builder;

use CrasyHorse\PhpunitXrayReporter\Xray\Types\TestInfo;

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

    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @return $this
     */
    public function setSummary(string $summary)
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function getProjectKey(): string
    {
        return $this->projectKey;
    }

    /**
     * @return $this
     */
    public function setProjectKey(string $projectKey)
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
     * @return $this
     */
    public function setTestType($testType)
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
     * @return $this
     */
    public function setRequirementKeys($requirementKeys)
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
     * @return $this
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;

        return $this;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }

    /**
     * @return $this
     */
    public function setDefinition(string $definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Builds a class of type XrayType.
     *
     * @return TestInfo
     */
    public function build()
    {
        return new TestInfo($this);
    }
}
