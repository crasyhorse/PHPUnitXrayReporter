<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;
use JsonSerializable;

/**
 * Represents a Xray "Info" object.
 * 
 * @author Paul Friedemann
 */
class Info implements JsonSerializable
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
     * @var array
     */
    private $testEnvironments;

    public function __construct(string $project, string $summary, string $description, string $version, string $revision, string $user, string $testPlanKey, array $testEnvironments)
    {
        $this->project = $project;
        $this->summary = $summary;
        $this->description = $description;
        $this->version = $version;
        $this->revision = $revision;
        $this->user = $user;
        $this->testPlanKey = $testPlanKey;
        $this->testEnvironments = $testEnvironments;
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
