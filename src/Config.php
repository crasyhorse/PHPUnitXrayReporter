<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter;

use Exception;
use InvalidArgumentException;

/**
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

    public function __construct($configDir)
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

    public function getTestEnvironments(): array
    {
        return $this->testEnvironments;
    }

    private function readJsonFile($configDir): void
    {
        if (file_exists($configDir)) {
            $json_content = json_decode(file_get_contents($configDir));
        } else {
            // TODO: This Exception only shows the Message without any hint or stack trace
            throw new InvalidArgumentException('The needed config file could not be found on the given path: '.$configDir);
        }
        $this->testExecutionKey = $json_content->{'testExecutionKey'} ?? '';
        $this->project = $json_content->{'info'}->{'project'} ?? '';
        $this->summary = $json_content->{'info'}->{'summary'} ?? '';
        $this->description = $json_content->{'info'}->{'description'} ?? '';
        $this->version = $json_content->{'info'}->{'version'} ?? '';
        $this->revision = $json_content->{'info'}->{'revision'} ?? '';
        $this->user = $json_content->{'info'}->{'user'} ?? '';
        $this->testPlanKey = $json_content->{'info'}->{'testPlanKey'} ?? '';
        $this->testEnvironments = $json_content->{'info'}->{'testEnvironments'} ?? '';
    }
}
