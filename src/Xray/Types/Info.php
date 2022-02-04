<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

use JsonSerializable;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\XrayType;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\InfoBuilder;

/**
 * Represents a Xray "Info" object.
 * 
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Info implements JsonSerializable, XrayType
{
    /**
     * The project key where the test execution will be created.
     * 
     * @var string
     */
    private $project;

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
    private $version;

    /**
     * @var string
     */
    private $revision;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $testPlanKey;

    /**
     * @var array<int, string>
     */
    private $testEnvironments;

    public function __construct(InfoBuilder $infoBuilder)
    {
        $this->project = $infoBuilder->getProject();
        $this->summary = $infoBuilder->getSummary();
        $this->description = $infoBuilder->getDescription();
        $this->version = $infoBuilder->getVersion();
        $this->revision = $infoBuilder->getRevision();
        $this->user = $infoBuilder->getUser();
        $this->testPlanKey = $infoBuilder->getTestPlanKey();
        $this->testEnvironments = $infoBuilder->getTestEnvironments();
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'project' => $this->project,
            'summary' => $this->summary,
            'description' => $this->description,
            'version' => $this->version,
            'revision' => $this->revision,
            'user' => $this->user,
            'testPlanKey' => $this->testPlanKey,
            'testEnvironments' => $this->testEnvironments,
        ];
    }
}
