<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Builder;

use Adbar\Dot;
use CrasyHorse\PhpunitXrayReporter\Config\Config;
use CrasyHorse\PhpunitXrayReporter\Xray\Types\Info;
use CrasyHorse\PhpunitXrayReporter\Xray\Types\Test;
use CrasyHorse\PhpunitXrayReporter\Xray\Types\TestExecution;
use CrasyHorse\PhpunitXrayReporter\Xray\Types\TestInfo;
use CrasyHorse\PhpunitXrayReporter\Exceptions\InvalidArgumentException;

class BuilderHandler
{
    /**
     * @var Info
     */
    private $info;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->info = $this->buildInfo();
    }

    /**
     * Builds the XRAY type "info" object.
     *
     * @return Info
     */
    public function buildInfo()
    {
        /** @var string */
        $project = $this->config->get('info.project');
        $info = (new InfoBuilder())->setProject($project);

        /** @var string */
        $summary = $this->config->get('info.summary');
        $info = $info->setSummary($summary);
        
        /** @var string */
        $description = $this->config->get('info.description');
        $info = $info->setDescription($description);
        
        /** @var string */
        $version = $this->config->get('info.version');
        $info = $info->setVersion($version);
        
        /** @var string */
        $revision = $this->config->get('info.revision');
        $info = $info->setRevision($revision);
        
        /** @var string */
        $user = $this->config->get('info.user');
        $info = $info->setUser($user);
        
        /** @var string */
        $testPlanKey = $this->config->get('info.testPlanKey');
        $info = $info->setTestPlanKey($testPlanKey);
        
        /** @var array<array-key, string> */
        $testEnvironments = $this->config->get('info.testEnvironments');
        $info = $info->setTestEnvironments($testEnvironments);

        $this->info = $info->build();

        return $this->info;
    }

    /**
     * Builds the Xray type "Test" object.
     *
     * @param array<array-key, string> $result A single PHPUnit test result
     *
     * @return Test
     */
    public function buildTest(array $result)
    {
        $test = (new TestBuilder())
                ->setName($result['name'])
                ->setStart($result['start'])
                ->setFinish($result['finish'])
                ->setComment($result['comment']);

        /** @var "PASS" | "FAIL"  | "TODO" $status */
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

        $testInfo = $this->buildTestInfo($result);
        $test = $test->setTestInfo($testInfo);

        return $test->build();
    }

    /**
     * Builds the Xray type "TestExecution".
     *
     * If the annotation is given without an attribute, an exception will be thrown.
     *
     * @param array<array-key, string> $data Parsed data of the @XRAY-testExecutionKey annotation
     * 
     * @return TestExecution|null
     * @throws InvalidArgumentException
     */
    public function buildTestExecution(array $data)
    {
        $testExecutionDot = new Dot($data);

        /** @var string $testExecutionKey */
        $testExecutionKey = $testExecutionDot->get('XRAY-testExecutionKey') ?? $this->config->get('testExecutionKey');

        if ($testExecutionDot->has('XRAY-testExecutionKey') && $testExecutionDot->isEmpty('XRAY-testExecutionKey')) {
            /** @var string $name */
            $name = $testExecutionDot->get('name');
            throw new InvalidArgumentException("XRAY-testExecutionKey has to be set, if annotation is given. Have you forgotten it? It is not set in test case: {$name}");
        }
        
        if (empty($testExecutionKey)) {
            return null;
        }
        
        return new TestExecution($testExecutionKey);
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
     * 4) Last option is to get the projectKey either from testExecutionKey or testKey
     *      in the doc block comment of the test. With this information, the project value of the Info object
     *      can be filled to import this new testExecution.
     *
     * @param array<array-key,string> $result
     *
     * @return TestInfo
     */
    public function buildTestInfo(array $result)
    {
        $testInfo = new TestInfoBuilder();
        $projectKey = $this->computeProjectKey($result);

        if ($projectKey) {
            $testInfo = $testInfo->setProjectKey($projectKey);
        } else {
            throw new InvalidArgumentException("No projectKey could be found or generated for test case: {$result['name']}\nThe project value in the info object of config file has to be set, if tests exist, where no testExecutionKey is given or the testExecutionKey in the config file is empty!");
        }

        if($this->info) {
            $this->info->setProject($projectKey);
        }

        $testInfo = $testInfo->setTestType($result['XRAY-TESTINFO-testType']);
        $testInfo = $testInfo->setRequirementKeys($result['XRAY-TESTINFO-requirementKeys']);
        $testInfo = $testInfo->setLabels($result['XRAY-TESTINFO-labels']);
        $testInfo = $testInfo->setDefinition($result);
        $testInfo = $testInfo->setSummary($result);


        if (!empty($result['description'])) {
            $description = $result['description'];
            $testInfo = $testInfo->setDescription($description);
        }

        return $testInfo->build();
    }

    /**
     * Strips off the number behind the testExecutionKey or testKey. Afterwards the projectKey will be returned.
     * 
     * @param string|null $key A Jira issue key
     * 
     * @return string|null
     */
    private function stripOfKeyNumber($key)
    {
        $subject = $key ?? '';
        preg_match('/([0-9a-zA-Z]+)(?=-)/', $subject, $matches);

        if (count($matches) > 0) {
            return $matches[0];
        }

        return null;
    }

    /**
     * Uses different approaches to compute the projectKey attribute.
     * 
     * @param array<array-key,mixed> $result The test results
     * 
     * @return string
     */
    private function computeProjectKey(array $result): string
    {
        return $result['XRAY-TESTINFO-projectKey'] ?? 
            $this->stripOfKeyNumber($this->config->get('testExecutionKey')) ?? 
            $this->config->get('info.project') ??
            $this->stripOfKeyNumber($result['XRAY-testKey']) ??
            $this->stripOfKeyNumber($result['XRAY-testExecutionKey']);
    }
}
