<?php

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
    private $key;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var array<array-key, string>
     */
    private $defects;

    /**
     * @var TestInfo
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
     * @var string
     */
    private $status;

    public function __construct(TestBuilder $testBuilder)
    {
        $this->key = $testBuilder->getKey();
        $this->comment = $testBuilder->getcomment();
        $this->defects = $testBuilder->getDefects();
        $this->start = $testBuilder->getStart();
        $this->finish = $testBuilder->getFinish();
        $this->status = $testBuilder->getStatus();
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'testKey' => $this->key,
            'comment' => $this->comment,
            'defects' => $this->defects,
            'start' => $this->start,
            'finish' => $this->finish,
            'status' => $this->status,
            'testInfo' => $this->testInfo
        ];
    }
}