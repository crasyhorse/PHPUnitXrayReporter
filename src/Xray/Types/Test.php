<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;
use JsonSerializable;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\XrayType;
use Crasyhorse\PhpunitXrayReporter\Xray\Builder\TestBuilder;

/**
 * Represents a Xray "Test" object.
 * 
 * @author Florian Weidinger
 */
class Test implements JsonSerializable, XrayType {

    /**
     * @var string
     */
    private $testKey;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var array<array-key, string>
     */
    private $defects;

    /**
     * @var TestInfo|null
     */
    private $testInfo;

    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $finish;

    /**
     * @var "PASS" | "FAIL"
     */
    private $status;

    public function __construct(TestBuilder $testBuilder)
    {
        $this->testKey = $testBuilder->getTestKey();
        $this->comment = $testBuilder->getcomment();
        $this->defects = $testBuilder->getDefects();
        $this->start = $testBuilder->getStart();
        $this->finish = $testBuilder->getFinish();
        $this->status = $testBuilder->getStatus();
        $this->testInfo = $testBuilder->getTestInfo();
    }

    public function getTestKey(): string
    {
        return $this->testKey;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
    
    /**
     * @return array<array-key, string>
     */
    public function getDefects()
    {
        return $this->defects;
    }
    
    /**
     * @return TestInfo|null
     */
    public function getTestInfo()
    {
        return $this->testInfo;
    }
    
    public function getStart(): string
    {
        return $this->start;
    }
    
    public function getFinish(): string
    {
        return $this->finish;
    }
    
    /**
     * @return "PASS" | "FAIL"
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Defines how this class is serialized into JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $json = [];
        foreach(['testKey', 'comment', 'defects', 'start', 'finish', 'status'] as $attribute) {
            if(!empty($this->{$attribute})) {
                /** @psalm-suppress MixedAssignment */
                $json[$attribute] = $this->{$attribute};
            }
        }

        /** @psalm-suppress PossiblyNullReference */
        if(!$this->getTestInfo()->isEmpty()) {
            $json['testInfo'] = $this->getTestInfo();
        }

        return $json;
    }
}