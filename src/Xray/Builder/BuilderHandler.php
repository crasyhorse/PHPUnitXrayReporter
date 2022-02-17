<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Builder;

use Crasyhorse\PhpunitXrayReporter\Config;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Info;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\Test;
use Crasyhorse\PhpunitXrayReporter\XRAY\Types\TestExecution;
use Crasyhorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use InvalidArgumentException;

class BuilderHandler
{
    /**
     * @var Info
     */
    private $info;

    /**
     * Builds the Xray type "TestExecution".
     *
     * 1) If the TestExecution object has a testExecutionKey attribute, it will be added to the list of updatable
     * test executions.
     * 2) If not and the TestExecutionKey is specified in the config, it will take this key and create a new TestExecution.
     * 3) Otherwise, it is treated as a new test executions that should be imported into Xray and gets the info object.
     *
     * If the annotation is given without an attribute, an exception will be thrown.
     *
     * @param array<array-key, string> $testExecution
     *
     * @return void
     */
    public function buildTestExecution(Config $config, array $testExecution, &$testExecutionsToUpdate, &$testExecutionToImport): void
    {
        if (array_key_exists('XRAY-testExecutionKey', $testExecution)) {
            $testExecutionKey = $testExecution['XRAY-testExecutionKey'];
            if (empty($testExecutionKey)) {
                throw new InvalidArgumentException('XRAY-testExecutionKey has to be set, if annotation is given. Have you forgotten it? It is not set in test case: '.$testExecution['name']);
            }
            if (empty($testExecutionsToUpdate[$testExecutionKey])) {
                $testExecutionsToUpdate[$testExecutionKey] = new TestExecution($testExecutionKey);
            }
        } elseif (!empty($config->getTestExecutionKey())) {
            $testExecutionKey = $config->getTestExecutionKey();
            $testExecutionsToUpdate[$testExecutionKey] = new TestExecution($testExecutionKey);
        } else {
            $testExecutionToImport = new TestExecution();
            $testExecutionToImport->addInfo($this->buildInfo($config));
        }
    }

    /**
     * Builds the XRAY type "info" object.
     *
     * @param Config
     *
     * @return Info
     */
    public function buildInfo(Config $config): Info
    {
        $info = (new InfoBuilder())
            ->setProject($config->getProject());

        if (!empty($config->getSummary())) {
            $info = $info->setSummary($config->getSummary());
        }
        if (!empty($config->getDescription())) {
            $info = $info->setDescription($config->getDescription());
        }
        if (!empty($config->getVersion())) {
            $info = $info->setVersion($config->getVersion());
        }
        if (!empty($config->getRevision())) {
            $info = $info->setRevision($config->getRevision());
        }
        if (!empty($config->getUser())) {
            $info = $info->setUser($config->getUser());
        }
        if (!empty($config->getTestPlanKey())) {
            $info = $info->setTestPlanKey($config->getTestPlanKey());
        }
        if (!empty($config->getTestEnvironments())) {
            $info = $info->setTestEnvironments($config->getTestEnvironments());
        }
        $this->info = $info->build();

        return $this->info;
    }

    /**
     * Builds the Xray type "Test" object.
     *
     * @param array<array-key, string> $result
     *
     * @return Test
     */
    public function buildTest(Config $config, array $result): Test
    {
        $test = (new TestBuilder())
                ->setName($result['name'])
                ->setStart($result['start'])
                ->setFinish($result['finish'])
                ->setComment($result['comment']);

        /** @var "PASS" | "FAIL" $status */
        $status = $result['status'];

        $test = $test->setStatus($status);

        if (array_key_exists('XRAY-TESTS-testKey', $result)) {
            $testKey = $result['XRAY-TESTS-testKey'];
            if (empty($testKey)) {
                throw new InvalidArgumentException('XRAY-TESTS-testKey has to be set, if annotation is given. Have you forgotten it? It is not set in test case: '.$result['name']);
            }
            $test = $test->setTestKey($testKey);
        }

        if (!empty($result['XRAY-TESTS-defects'])) {
            /** @var array<array-key, string> $defects */
            $defects = $result['XRAY-TESTS-defects'];
            $test = $test->setDefects($defects);
        }

        $testInfo = $this->buildTestInfo($config, $result);
        $test = $test->setTestInfo($testInfo);

        return $test->build();
    }

    /**
     * Builds the Xray type "TestInfo" object.
     * Because projectKey is required, it tries to get the key in several ways:.
     *
     * 1) The projectKey is given by tag in the doc block of the PHPunit test
     * 2) If the testExecutionKey is set in the config file, it strips of the key nummbers
     *      and results in the projectKey
     * 3) If 2) is not possible, the projectKey should be given in the config file as project, because
     *      either 2) or 3) is necessary if no testExecution is given in the doc blocks of all tests
     * 4-5) Last option is to get the projectKey either from testExecutionKey or testKey
     *      in the doc block comment of the test. With this information, the project value of the Info object
     *      can be filled to import this new testExecution.
     *
     * @param array<array-key,string> $result
     *
     * @return TestInfo
     */
    public function buildTestInfo(Config $config, array $result): TestInfo
    {
        $testInfo = new TestInfoBuilder();
        if (!empty($result['XRAY-TESTINFO-projectKey'])) {
            $projectKey = $result['XRAY-TESTINFO-projectKey'];
            $testInfo = $testInfo->setProjectKey($projectKey);
        } elseif (!empty($config->getTestExecutionKey())) {
            $projectKey = $this->stripOfKeyNumber($config->getTestExecutionKey());
            $testInfo = $testInfo->setProjectKey($projectKey);
        } elseif (!empty($this->info->getProject())) {
            $projectKey = $config->getProject();
            $testInfo = $testInfo->setProjectKey($projectKey);
        } elseif (!empty($result['XRAY-testKey'])) {
            $projectKey = $this->stripOfKeyNumber($result['XRAY-testKey']);
            $this->info->setProject($projectKey);
            $testInfo = $testInfo->setProjectKey($projectKey);
        } elseif (!empty($result['XRAY-testExecutionKey'])) {
            $projectKey = $this->stripOfKeyNumber($result['XRAY-testExecutionKey']);
            $this->info->setProject($projectKey);
            $testInfo = $testInfo->setProjectKey($projectKey);
        } else {
            throw new InvalidArgumentException('No projectKey could be found or generated for test case: '.$result['name'].'\nThe project value in the info object of config file has to be set, if tests exist, where no testExecutionKey is given or the testExecutionKey in the config file is empty!');
        }

        if (!empty($result['XRAY-TESTINFO-testType'])) {
            /** @var "Generic" | "Cumcumber" | null $testType */
            $testType = $result['XRAY-TESTINFO-testType'];
            $testInfo = $testInfo->setTestType($testType);
        }

        if (!empty($result['XRAY-TESTINFO-requirementKeys'])) {
            /** @var array<array-key, string> $requirementKeys */
            $requirementKeys = $result['XRAY-TESTINFO-requirementKeys'];
            $testInfo = $testInfo->setRequirementKeys($requirementKeys);
        }

        if (!empty($result['XRAY-TESTINFO-labels'])) {
            /** @var array<array-key, string> $labels */
            $labels = $result['XRAY-TESTINFO-labels'];
            $testInfo = $testInfo->setLabels($labels);
        }

        if (!empty($result['XRAY-TESTINFO-definition'])) {
            $definition = $result['XRAY-TESTINFO-definition'];
            $testInfo = $testInfo->setDefinition($definition);
        }

        if (!empty($result['summery'])) {
            $summary = $result['summery'];
            $testInfo = $testInfo->setSummary($summary);
        } else {
            $summary = $result['name'];
            $testInfo = $testInfo->setSummary($summary);
        }

        if (!empty($result['description'])) {
            $description = $result['description'];
            $testInfo = $testInfo->setDescription($description);
        }

        return $testInfo->build();
    }

    /**
     * Strips off the number behind the testExecutionKey or testKey. Afterwards the projectKey will be returned.
     *
     * @return string
     */
    private function stripOfKeyNumber(string $key): string
    {
        preg_match('/([0-9a-zA-Z]+)(?=-)/', $key, $matches);

        return $matches[0];
    }
}
