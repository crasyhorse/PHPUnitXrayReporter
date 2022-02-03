<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * Represents a Xray "Test" object.
 * 
 * @author Florian Weidinger
 */
class Test implements Serializable{

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
     * @param array<array-key, string> $defects
     */
    public function __construct(string $key, string $comment, array $defects)
    {
        $this->key = $key;
        $this->comment = $comment;
        $this->defects = $defects;
    }

    /**
     * Serializes the class into a JSON string.
     * 
     * @return string
     */
    public function toJson(): string
    {
        return json_encode([
            'testKey' => $this->key,
            'comment' => $this->comment,
            'defects' => $this->defects
        ]);
    }
}