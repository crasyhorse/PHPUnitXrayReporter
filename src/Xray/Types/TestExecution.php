<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

use JsonSerializable;

/**
 * Represents an XRay "Test execution" object.
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
     * @var Info|null
     */
    private $info;

    /**
     * @var array<array-key, Test>
     */
    private $tests = [];

    /**
     * @param array<array-key, Test> $tests
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Adds an Xray "Test" object.
     *
     * @return void
     */
    public function addTest(Test $test): void
    {
        $this->tests[] = $test;
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
            'tests' => $this->tests,
        ];
    }

    public function setInfo(Info $info): void
    {
        $this->info = $info;
    }
}
