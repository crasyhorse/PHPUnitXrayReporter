<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

use Crasyhorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use JsonSerializable;

/**
 * Represents an XRay "Test execution" object.
 *
 * @author Florian Weidinger
 */
class TestExecution implements JsonSerializable
{
    /**
     * @var string|null
     */
    private $key;

    /**
     * @var array<array-key, Test>
     */
    private $tests = [];

    /**
     * @param array<array-key, Test> $tests
     */
    public function __construct(string $key = null)
    {
        $this->key = $key;
    }

    /**
     * @return string|null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Adds an Xray "Test" object if its status has changed
     * to FAIL or if it does not exist.
     *
     * @return void
     */
    public function addTest(Test $value): void
    {
        if (count($this->tests) === 0) {
            $this->tests[$value->getTestKey()] = $value;
        }

        foreach ($this->tests as $test) {
            if (($test->getTestKey() === $value->getTestKey() &&
                $value->getStatus() === FailedTest::TEST_RESULT) ||
                $test->getTestKey() !== $value->getTestKey()) {
                $this->tests[$value->getTestKey()] = $value;
            }
        }
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
            'tests' => $this->tests,
        ];
    }
}
