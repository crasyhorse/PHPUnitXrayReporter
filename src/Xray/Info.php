<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * @author Paul Friedemann
 */
class Info implements Serializable
{
    /**
     * @var string
     */
    private $projectKey;

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

    public function __construct(string $projectKey, string $summary, string $description, string $version, string $revision, string $user, string $testPlanKey, array $testEnvironments)
    {
        $this->projectKey = $projectKey;
        $this->summary = $summary;
        $this->description = $description;
        $this->version = $version;
        $this->revision = $revision;
        $this->user = $user;
        $this->testPlanKey = $testPlanKey;
        $this->testEnvironments = $testEnvironments;
    }

    public function toJson(): string
    {
        return json_encode([
            'projectKey' => $this->projectKey,
            'summary' => $this->summary,
            'description' => $this->description,
            'version' => $this->version,
            'revision' => $this->revision,
            'user' => $this->user,
            'testPlanKey' => $this->testPlanKey,
            'testEnvironments' => $this->testEnvironments,
        ]);
    }
}
