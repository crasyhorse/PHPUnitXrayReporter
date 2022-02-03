<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * Represents a Xray "Test" object.
 * 
 * @author Florian Weidinger
 */
class Test {

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

    public function toJson(): string
    {
        // code
    }
}