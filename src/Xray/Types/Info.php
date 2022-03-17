<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Types;

use CrasyHorse\PhpunitXrayReporter\Xray\Builder\InfoBuilder;
use JsonSerializable;

/**
 * Represents an Xray "Info" object.
 *
 * @author Paul Friedemann
 */
class Info implements JsonSerializable, XrayType
{
    /**
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
     * @var array<string>
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

    public function setProject(string $project): void
    {
        $this->project = $project;
    }

    public function getProject(): string
    {
        return $this->project;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getRevision(): string
    {
        return $this->revision;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getTestPlanKey(): string
    {
        return $this->testPlanKey;
    }

    /**
     * @return array<string>
     */
    public function getTestEnvironments()
    {
        return $this->testEnvironments;
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return array<array-key, string>
     */
    public function jsonSerialize()
    {
        $json = [];
        foreach (['project', 'summary', 'description', 'version', 'revision', 'user', 'testPlanKey', 'testEnvironments'] as $attribute) {
            if (!empty($this->{$attribute})) {
                /** @var string|array */
                $json[$attribute] = $this->{$attribute};
            }
        }

        return $json;
    }
}
