<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;
use JsonSerializable;

/**
 * Represents a XRay "Test execution" object.
 *
 * @author Florian Weidinger
 */
class TestExecution implements JsonSerializable
{
    /**
     * @var string
     */
    private $key = '';

    /**
     * @var Info
     */
    private $info;

    /**
     * @var array<array-key, Test>
     */
    private $tests = [];

    /**
     * @param array<array-key, Test> $tests
     */
    public function __construct(string $key, Info $info, array $tests)
    {
        $this->key = $key;
        $this->info = $info;
        $this->tests = $tests;
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'testExecutionKey' => $this->key,
            'info' => $this->info,
            'tests' => $this->tests
        ];
    }
}
