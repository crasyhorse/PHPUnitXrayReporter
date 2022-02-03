<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;
use JsonSerializable;

/**
 * Represents a Xray "TestInfo" object.
 *
 * @author Florian Weidinger
 */
class TestInfo implements JsonSerializable
{
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
     * Defines how this class is serialized into JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'projectKey' => $this->projectKey,
            'testType' => $this->testType,
            'requirementsKeys' => $this->requirementsKeys,
            'labels' => $this->labels,
            'definition' => $this->definition,
        ];
    }
}
