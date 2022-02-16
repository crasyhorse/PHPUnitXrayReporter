<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Assets;

/**
 * Claims to be a Test class. It is used in CrasyHorse\Tests\Unit\ParserTest to
 * have specs with doc blocks for generation of test result.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class PseudoSpec
{
    /**
     * Update test execution DEMO-666.
     * This test will return a PASS result and has all possible annotations we implemented.
     *
     * @test
     * @XRAY-testExecutionKey DEMO-666
     *
     * @XRAY-TESTS-testKey DEMO-123
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects DEMO-1,DEMO-2
     *
     * @XRAY-TESTINFO-projectKey DEMO
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys DEMO-1,DEMO-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test does nothing
     */
    public function spec1(): void
    {
        // code...
    }

    /**
     * Update test execution DEMO-667 with little information.
     *
     * @XRAY-testExecutionKey DEMO-667
     * @XRAY-TESTS-testKey DEMO-123
     */
    public function spec2(): void
    {
        // code...
    }

    /**
     * Successful test result with Test and TestInfo objects.
     *
     * @test
     * @XRAY-testExecutionKey DEMO-668
     *
     * @XRAY-TESTS-testKey DEMO-123
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects DEMO-1,DEMO-2
     *
     * @XRAY-TESTINFO-projectKey DEMO
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys DEMO-1,DEMO-2,DEMO-3
     * @XRAY-TESTINFO-labels workInProgress,demo
     * @XRAY-TESTINFO-definition Let's test
     */
    public function spec3(): void
    {
        // code...
    }

    /**
     * Update test execution DEMO-669.
     * This is really cool!
     *
     * @XRAY-testExecutionKey DEMO-669
     *
     * @XRAY-TESTS-testKey DEMO-124
     * @XRAY-TESTS-comment This Test should return FAIL
     * @XRAY-TESTS-defects DEMO-1,DEMO-2
     *
     * @XRAY-TESTINFO-projectKey DEMO
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys DEMO-1,DEMO-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test does nothing
     */
    public function spec4(): void
    {
        // code...
    }

    public function spec5(): void
    {
        // code
    }
}
