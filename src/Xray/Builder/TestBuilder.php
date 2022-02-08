<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Builder;

use Crasyhorse\PhpunitXrayReporter\Xray\Types\XrayType;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Test;

class TestBuilder implements Builder {

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
     * @var "PASS" | "FAIL"
     */
    private $status = 'FAIL';
    
    public function __construct(){
        // Intentionally left blank
    }

    public function getTestKey(): string
    {
        return $this->testKey;
    }

    public function setTestKey(string $testKey): self
    {
        $this->testKey = $testKey;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
    
    public function setComment(string $comment): self
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
     */
    public function setDefects($defects): self
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
    
    public function setTestInfo(TestInfo $testInfo): self
    {
        $this->testInfo = $testInfo;
        return $this;
    }

    public function getStart(): string
    {
        return $this->start;
    }
    
    public function setStart(string $start): self
    {
        $this->start = $start;
        return $this;
    }

    public function getFinish(): string
    {
        return $this->finish;
    }
    
    public function setFinish(string $finish): self
    {
        $this->finish = $finish;
        return $this;
    }

    /**
     * @return "PASS" | "FAIL"
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param "PASS" | "FAIL" $status
     */
    public function setStatus($status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Builds a class of type XrayType.
     * 
     * @return Test
     */
    public function build(): Test {
        return new Test($this);
    }

}