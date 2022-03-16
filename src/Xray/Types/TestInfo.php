<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestInfoBuilder;
use JsonSerializable;

/**
 * Represents an Xray "TestInfo" object.
 *
 * @author Florian Weidinger
 */
class TestInfo implements JsonSerializable, XrayType
{
    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $projectKey;

    /**
     * @var "Generic" | "Cumcumber" | null
     */
    private $testType;

    /**
     * @var array<array-key,string>
     */
    private $requirementKeys;

    /**
     * @var array<array-key,string>
     */
    private $labels;

    /**
     * @var string
     */
    private $definition;

    public function __construct(TestInfoBuilder $testInfoBuilder)
    {
        $this->summary = $testInfoBuilder->getSummary();
        $this->description = $testInfoBuilder->getDescription();
        $this->projectKey = $testInfoBuilder->getProjectKey();
        $this->testType = $testInfoBuilder->getTestType();
        $this->requirementKeys = $testInfoBuilder->getRequirementKeys();
        $this->labels = $testInfoBuilder->getLabels();
        $this->definition = $testInfoBuilder->getDefinition();
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return array<array-key, mixed>
     */
    public function jsonSerialize()
    {
        $json = [];
        foreach (['projectKey', 'summary', 'description', 'testType', 'requirementKeys', 'labels', 'definition'] as $attribute) {
            if (!empty($this->{$attribute})) {
                /** @var string|array */
                $json[$attribute] = $this->{$attribute};
            }
        }

        return $json;
    }

    /**
     * Returns true if all attributes of the TestInfo object are empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->projectKey) &&
            empty($this->testType) &&
            empty($this->requirementKeys) &&
            empty($this->labels) &&
            empty($this->definition);
    }
}
