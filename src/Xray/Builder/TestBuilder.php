<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray\Builder;

use Crasyhorse\PhpunitXrayReporter\Xray\Types\XrayType;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Test;

class TestBuilder implements Builder {

    /**
     * @var string
     */
    private $key = '';

    /**
     * @var string
     */
    private $comment = '';

    /**
     * @var array<array-key, string>
     */
    private $defects = [];

    /**
     * @var TestInfo
     */
    private $testInfo;

    /**
     * @var string
     */
    private $start = '';

    /**
     * @var string
     */
    private $finish = '';

    /**
     * @var "PASS" | "FAIL" | "ERROR" | "WARNING" | "RISKY"
     */
    private $status = 'FAIL';
    
    public function __construct(){
        // Intentionally left blank
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
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

    public function getTestInfo(): TestInfo
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
     * @return "PASS" | "FAIL" | "ERROR" | "WARNING" | "RISKY"
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param "PASS" | "FAIL" | "ERROR" | "WARNING" | "RISKY" $status
     */
    public function setStatus($status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Builds a class of type XrayType.
     * 
     * @return XrayType
     */
    public function build(): XrayType {
        return new Test($this);
    }

}