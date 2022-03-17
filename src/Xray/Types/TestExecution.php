<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Types;

use CrasyHorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\TodoTest;
use JsonSerializable;

/**
 * Represents an XRay "Test execution" object.
 *
 * @author Florian Weidinger
 */
class TestExecution implements JsonSerializable, XrayType
{
    /**
     * @var string|null
     */
    private $key;

    /**
     * @var Info|null
     */
    private $info;

    /**
     * @var array<array-key, Test>
     */
    private $tests = [];

    /**
     * @param string|null $key A Jira issue key representing a test execution ticket
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

    public function addInfo(Info $value): void
    {
        $this->info = $value;
    }

    /**
     * Adds an Xray "Test" object if its status has changed
     * to FAIL or if it does not exist.
     *
     * @return void
     */
    public function addTest(Test $newTest): void
    {
        if (count($this->tests) === 0) {
            $this->tests[] = $newTest;
        } else {
            $counter = 0;

            foreach ($this->tests as $test) {
                if ($test->getName() == $newTest->getName()) {
                    $this->shouldOverwrite($test->getStatus(), $newTest, $counter);
                    $counter = -1;
                    break;
                }

                ++$counter;
            }

            if ($counter != -1) {
                $this->tests[] = $newTest;
            }
        }
    }

    /**
     * Defines how this class is serialized into JSON.
     *
     * @return array<array-key, mixed>
     */
    public function jsonSerialize()
    {
        $json = [];

        if (!empty($this->key)) {
            $json['testExecutionKey'] = $this->key;
        }

        if (!empty($this->info)) {
            $json['info'] = $this->info;
        }

        $json['tests'] = $this->tests;

        return $json;
    }

    /**
     * Decides whether the given Test has to be overwriten.
     *
     * It follows the following criteria:
     * 1) A failed test will overwrite every given test
     * 2) A todo test (skipped or incomplete from phpunit) just overwrites a successful test
     *    because a failure Message is more important
     * 3) A successful test does nothing, because the given test should already have the correct status.
     *
     * @param "PASS" | "FAIL" | "TODO" $oldTestStatus
     */
    private function shouldOverwrite($oldTestStatus, Test $test, int $index): void
    {
        if ($test->getStatus() == FailedTest::TEST_RESULT ||
            ($test->getStatus() == TodoTest::TEST_RESULT && $oldTestStatus === SuccessfulTest::TEST_RESULT)) {
            $this->tests[$index] = $test;
        }
    }
}
