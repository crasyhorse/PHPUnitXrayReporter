<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Builder;

use CrasyHorse\PhpunitXrayReporter\Xray\Types\Test;
use CrasyHorse\PhpunitXrayReporter\Xray\Types\TestInfo;

class TestBuilder implements Builder
{
    /**
     * @var string
     */
    private $testKey = '';

    /**
     * @var string
     */
    private $comment = '';

    /**
     * @var array<array-key, string>
     */
    private $defects = [];

    /**
     * @var TestInfo|null
     */
    private $testInfo = null;

    /**
     * @var string
     */
    private $start = '';

    /**
     * @var string
     */
    private $finish = '';

    /**
     * @var "PASS" | "FAIL" | "TODO"
     */
    private $status = 'FAIL';

    /**
     * @var string|null
     */
    private $name = null;

    public function getTestKey(): string
    {
        return $this->testKey;
    }

    /**
     * @return $this
     */
    public function setTestKey(string $testKey)
    {
        $this->testKey = $testKey;

        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return $this
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return array<array-key, string>
     */
    public function getDefects()
    {
        return $this->defects;
    }

    /**
     * @param array<array-key, string> $defects
     * @return $this
     */
    public function setDefects($defects)
    {
        $this->defects = $defects;

        return $this;
    }

    /**
     * @return TestInfo|null
     */
    public function getTestInfo()
    {
        return $this->testInfo;
    }

    /**
     * @return $this
     */
    public function setTestInfo(TestInfo $testInfo)
    {
        $this->testInfo = $testInfo;

        return $this;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @return $this
     */
    public function setStart(string $start)
    {
        $this->start = $start;

        return $this;
    }

    public function getFinish(): string
    {
        return $this->finish;
    }

    /**
     * @return $this
     */
    public function setFinish(string $finish)
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * @return "PASS" | "FAIL" | "TODO"
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param "PASS" | "FAIL" | "TODO" $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Builds a class of type XrayType.
     *
     * @return Test
     */
    public function build(): Test
    {
        return new Test($this);
    }
}
