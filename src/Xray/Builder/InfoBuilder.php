<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Builder;

use CrasyHorse\PhpunitXrayReporter\Xray\Types\Info;

class InfoBuilder implements Builder
{
    /**
     * @var string|null
     */
    private $project = null;

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
     * @var string|null
     */
    private $testPlanKey = null;

    /**
     * @var array<string>
     */
    private $testEnvironments = [];

    /**
     * @return string|null
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param string|null $project The project key. This is, e. g. DEMO-101 -> DEMO
     * 
     * @return $this
     */
    public function setProject($project)
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

    /**
     * @return string|null
     */
    public function getTestPlanKey()
    {
        return $this->testPlanKey;
    }

    /**
     * @param string|null $testPlanKey A Jira ticket number representing a test plan ticket.
     * 
     * @return $this
     */
    public function setTestPlanKey($testPlanKey)
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
