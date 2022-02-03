<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;
use JsonSerializable;

/**
 * Represents a Xray "Test" object.
 * 
 * @author Florian Weidinger
 */
class Test implements JsonSerializable{

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

    /**
     * @param array<array-key, string> $defects
     */
    public function __construct(string $key, string $comment, array $defects, string $start, string $finish, string $status, TestInfo $testInfo)
    {
        $this->key = $key;
        $this->comment = $comment;
        $this->defects = $defects;
        $this->start = $start;
        $this->finish = $finish;
        $this->status = $status;
        $this->testInfo = $testInfo;
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