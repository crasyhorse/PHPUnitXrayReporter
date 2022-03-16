<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter;

use InvalidArgumentException;

/**
 * Makes the contents of the configuration file accessible to the extension.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Config
{
    /**
     * @var string
     */
    private $testExecutionKey;

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

    public function __construct(string $configDir)
    {
        $this->readJsonFile($configDir);
    }

    public function getTestExecutionKey(): string
    {
        return $this->testExecutionKey;
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

    private function readJsonFile(string $configDir): void
    {
        if (file_exists($configDir)) {
            /** @var object $jsonContent */
            $jsonContent = json_decode(file_get_contents($configDir));
        } else {
            throw new InvalidArgumentException('The needed config file could not be found on the given path: '.$configDir);
        }

        $this->testExecutionKey = (string) ($jsonContent->testExecutionKey ?? '');

        /** @var object */
        $info = $jsonContent->info;

        $this->project = (string) ($info->project ?? '');
        $this->summary = (string) ($info->summary ?? '');
        $this->description = (string) ($info->description ?? '');
        $this->version = (string) ($info->version ?? '');
        $this->revision = (string) ($info->revision ?? '');
        $this->user = (string) ($info->user ?? '');
        $this->testPlanKey = (string) ($info->testPlanKey ?? '');

        /** @var array<array-key,string> */
        $this->testEnvironments = ($info->testEnvironments ?? '');
    }
}
