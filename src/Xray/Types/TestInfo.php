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
     * @var string
     */
    private $testType;

    /**
     * @var array<int, string>
     */
    private $requirementsKeys;

    /**
     * @var array<int, string>
     */
    private $labels;

    /**
     * @var string
     */
    private $definition;

    /**
     * @param array<int, string> $requirementsKeys
     * @param array<int, string> $labels
     */
    public function __construct(TestInfoBuilder $testInfoBuilder)
    {
        $this->projectKey = $testInfoBuilder->getProjectKey();
        $this->testType = $testInfoBuilder->getTestType();
        $this->requirementsKeys = $testInfoBuilder->getRequirementsKeys();
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
        return [
            'projectKey' => $this->projectKey,
            'testType' => $this->testType,
            'requirementsKeys' => $this->requirementsKeys,
            'labels' => $this->labels,
            'definition' => $this->definition,
        ];
    }
}
