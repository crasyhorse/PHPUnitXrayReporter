<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

use Crasyhorse\PhpunitXrayReporter\Xray\Builder\InfoBuilder;
use JsonSerializable;

/**
 * Represents a Xray "Info" object.
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

    public function setProject($project): void
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

    public function getVersion()
    {
        return $this->version;
    }

    public function getRevision()
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
     * @return mixed
     */
    public function jsonSerialize()
    {
        $json = [];
        foreach (['project', 'summary', 'description', 'version', 'revision', 'user', 'testPlanKey', 'testEnvironments'] as $attribute) {
            if (!empty($this->{$attribute})) {
                /* @psalm-suppress MixedAssignment */
                $json[$attribute] = $this->{$attribute};
            }
        }

        return $json;
    }
}
