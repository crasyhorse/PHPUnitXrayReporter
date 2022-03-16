<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Builder;

use Crasyhorse\PhpunitXrayReporter\Xray\Types\Info;

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

    public function setProject(string $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getRevision(): string
    {
        return $this->revision;
    }

    public function setRevision(string $revision): self
    {
        $this->revision = $revision;

        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTestPlanKey(): string
    {
        return $this->testPlanKey;
    }

    public function setTestPlanKey(string $testPlanKey): self
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
     */
    public function setTestEnvironments($testEnvironments): self
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
