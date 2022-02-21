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
     * @test
     *
     * @XRAY-TESTS-testKey DEMO-123
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
     * @XRAY-TESTS-testKey DEMO-124
     * @XRAY-TESTS-defects DEMO-1,DEMO-2
     *
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys DEMO-1,DEMO-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test does nothing
     */
    public function spec4(): void
    {
        // code...
    }

    /**
     * Update test execution DEMO-670.
     * This is really cool!
     *
     * @XRAY-testExecutionKey
     *
     * @XRAY-TESTS-testKey DEMO-124
     * @XRAY-TESTS-defects DEMO-1,DEMO-2
     *
     * @XRAY-TESTINFO-projectKey DEMO
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys DEMO-1,DEMO-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test does nothing
     */
    public function spec5(): void
    {
        // code
    }

    /**
     * Update test execution DEMO-671.
     * This is really cool!
     *
     * @XRAY-testExecutionKey DEMO-671
     *
     * @XRAY-TESTS-testKey
     * @XRAY-TESTS-defects DEMO-1,DEMO-2
     *
     * @XRAY-TESTINFO-projectKey DEMO
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys DEMO-1,DEMO-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test does nothing
     */
    public function spec6(): void
    {
        // code
    }

    /**
     * Update test execution DEMO-669.
     * This is really cool!
     *
     * @XRAY-TESTS-defects DEMO-1,DEMO-2
     *
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys DEMO-1,DEMO-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test does nothing
     */
    public function spec7(): void
    {
        // code
    }

    /**
     * @test
     */
    public function spec8(): void
    {
        // code
    }

    /**
     * @test
     */
    public function spec9(): void
    {
        // code
    }

    /**
     * @test
     */
    public function spec10(): void
    {
        // code
    }

    /**
     * @test
     */
    public function spec11(): void
    {
        // code
    }
}
