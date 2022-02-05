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
     * Update test execution DEMO-666 including the Info object.
     *
     * @XRAY-testExecutionKey DEMO-666
     * @XRAY-INFO-project DEMO
     * @XRAY-INFO-summary Update test execution DEMO-666 including the Info object
     * @XRAY-INFO-description This is some kind of a test double for testing the Parser class. That means it is only a pseudo spec.
     * @XRAY-INFO-version 1.0
     * @XRAY-INFO-revision 1.0.1
     * @XRAY-INFO-user CalamityCoyote
     * @XRAY-INFO-testEnvironments PHP-Unit
     * @XRAY-TESTS-testKey DEMO-123
     */
    public function spec1(): void
    {
        // code...
    }

    /**
     * Update test execution DEMO-667 including the Info object.
     *
     * @XRAY-testExecutionKey DEMO-667
     * @XRAY-TESTS-testKey DEMO-123
     */
    public function spec2(): void
    {
        // code...
    }

    /**
     * Update test execution DEMO-668 including the Info object.
     *
     * @XRAY-testExecutionKey DEMO-668
     * @XRAY-TESTS-testKey DEMO-123
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
}
