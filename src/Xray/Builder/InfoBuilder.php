<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Builder;

use CrasyHorse\PhpunitXrayReporter\Xray\Types\Info;

class InfoBuilder implements Builder
{
    /**
     * @var string
     */
    private $project = '';

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
    private $version = '';

    /**
     * @var string
     */
    private $revision = '';

    /**
     * @var string
     */
    private $user = '';

    /**
     * @var string
     */
    private $testPlanKey = '';

    /**
     * @var array<string>
     */
    private $testEnvironments = [];

    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * @return $this
     */
    public function setProject(string $project)
    {
        $this->project = $project;

        return $this;
    }

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

    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return $this
     */
    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }

    public function getRevision(): string
    {
        return $this->revision;
    }

    /**
     * @return $this
     */
    public function setRevision(string $revision)
    {
        $this->revision = $revision;

        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return $this
     */
    public function setUser(string $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getTestPlanKey(): string
    {
        return $this->testPlanKey;
    }

    /**
     * @return $this
     */
    public function setTestPlanKey(string $testPlanKey)
    {
        $this->testPlanKey = $testPlanKey;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getTestEnvironments()
    {
        return $this->testEnvironments;
    }

    /**
     * @param array<string> $testEnvironments
     * @return $this
     */
    public function setTestEnvironments($testEnvironments)
    {
        $this->testEnvironments = $testEnvironments;

        return $this;
    }

    /**
     * Builds a class of type XrayType.
     *
     * @return Info
     */
    public function build()
    {
        return new Info($this);
    }
}
