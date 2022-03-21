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
     *
     * @param array<array-key,mixed>|string $result A PHPUnit test result
     * @return $this
     */
    public function setSummary($result)
    {
        if (is_string($result)) {
            $this->summary = $result;
        }

        if (is_array($result)) {
            /** @var string $summary */
            $summary = $result['summary'] ?? $result['name'];

            $this->summary = $summary;
        }

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
        if ($testType) {
            $this->testType = $testType;
        }

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
        if ($requirementKeys) {
            $this->requirementKeys = $requirementKeys;
        }

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
        if ($labels) {
            $this->labels = $labels;
        }

        return $this;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }

    /**
     *
     * @param array<array-key,mixed>|string $result A PHPUnit test result
     * @return $this
     */
    public function setDefinition($result)
    {
        if (is_string($result)) {
            $this->definition = $result;
        }

        if (is_array($result)) {
            /** @var string $definition */
            $definition = $result['XRAY-TESTINFO-definition'] ?? $result['name'];

            $this->definition = $definition;
        }

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
