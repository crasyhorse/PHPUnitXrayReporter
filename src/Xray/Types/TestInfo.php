<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

use JsonSerializable;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\XrayType;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestInfoBuilder;
/**
 * Represents a Xray "TestInfo" object.
 *
 * @author Florian Weidinger
 */
class TestInfo implements JsonSerializable, XrayType
{
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
        $this->projectKey = $testInfoBuilder->getProjectKey();
        $this->testType = $testInfoBuilder->getTestType();
        $this->requirementKeys = $testInfoBuilder->getRequirementKeys();
        $this->labels = $testInfoBuilder->getLabels();
        $this->definition = $testInfoBuilder->getDefinition();
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $json = [];
        foreach(['projectKey', 'testType', 'requirementKeys', 'labels', 'definition'] as $attribute) {
            if(!empty($this->{$attribute})) {
                /** @psalm-suppress MixedAssignment */
                $json[$attribute] = $this->{$attribute};
            }
        }

        return $json;
    }

    public function isEmpty(): bool
    {
        return empty($this->projectKey) &&
            empty($this->testType) &&
            empty($this->requirementKeys) &&
            empty($this->labels) &&
            empty($this->definition);
    }
}
