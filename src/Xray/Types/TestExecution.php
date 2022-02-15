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
            $this->tests[] = $value;
        } else {
            $counter = 0;
            foreach ($this->tests as $test) {
                if ($test->getTestKey() == $value->getTestKey()) {
                    $this->decideToOverwrite($test->getStatus(), $value, $counter);
                    $counter = -1;
                    break;
                }
                ++$counter;
            }
            if ($counter != -1) {
                $this->tests[] = $value;
            }
        }
    }

    /**
     * Decides either the in the TestExecution given Test with the same TestKey is to overwrite or not
     * It follows the following criteria:
     * 1) A failed test will overwrite every given test
     * 2) A todo test (skipped or incomplete from phpunit) just overwrites a successful test
     *    because a failure Message is more important
     * 3) A successful test do nothing, because the given test should already have the right status.
     */
    private function decideToOverwrite(string $oldTestStatus, Test $value, int $index): void
    {
        if ($value->getStatus() == FailedTest::TEST_RESULT ||
            ($value->getStatus() == TodoTest::TEST_RESULT && $oldTestStatus === SuccessfulTest::TEST_RESULT)) {
            $this->tests[$index] = $value;
        }
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $json = [];
        if(!empty($this->key)) {
            $json['testExecutionKey'] = $this->key;
        }
        $json['tests'] = $this->tests; 

        return $json;
    }
}
