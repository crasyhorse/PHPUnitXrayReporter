<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

use Crasyhorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TodoTest;
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
            $this->tests[$value->getName()] = $value;
        } else {
            $testFound = false;
            foreach ($this->tests as $test) {
                if ($test->getTestKey() == $value->getTestKey()) {
                    $testFound = true;
                    $this->overwriteDecision($test->getStatus(), $value);
                }
            }
            if (!$testFound) {
                $this->tests[$value->getName()] = $value;
            }
        }
    }

    /**
     * Decides either the in the TestExecution given Test with the same TestKey is to overwrite or not
     * It follows the following criteria:
     * 1) A failed test will overwrite every given test
     * 2) A todo test (skipped or incomplete from phpunit) just overwrites a successful test
     *    because a failure Message is more important
     * 3) A successful test do nothing, because the given test should already have the right status
     * 
     * It returns either a test with same testKey was found or not
     * @return bool
     */
    private function overwriteDecision(string $oldTestStatus, Test $value): void {
        if ($value->getStatus() == FailedTest::TEST_RESULT) {
            $this->tests[$value->getName()] = $value;
        } else if ($value->getStatus() == TodoTest::TEST_RESULT && $oldTestStatus === SuccessfulTest::TEST_RESULT) {
            $this->tests[$value->getName()] = $value;
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
