<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter;

use Crasyhorse\PhpunitXrayReporter\Tags\Info\Description;
use Crasyhorse\PhpunitXrayReporter\Tags\Info\Project;
use Crasyhorse\PhpunitXrayReporter\Tags\Info\Revision;
use Crasyhorse\PhpunitXrayReporter\Tags\Info\Summary;
use Crasyhorse\PhpunitXrayReporter\Tags\Info\TestEnvironments;
use Crasyhorse\PhpunitXrayReporter\Tags\Info\TestPlanKey;
use Crasyhorse\PhpunitXrayReporter\Tags\Info\User;
use Crasyhorse\PhpunitXrayReporter\Tags\Info\Version;
use Crasyhorse\PhpunitXrayReporter\Tags\TestExecutionKey;
use Crasyhorse\PhpunitXrayReporter\Tags\TestInfo\Definition;
use Crasyhorse\PhpunitXrayReporter\Tags\TestInfo\Labels;
use Crasyhorse\PhpunitXrayReporter\Tags\TestInfo\ProjectKey;
use Crasyhorse\PhpunitXrayReporter\Tags\TestInfo\RequirementKeys;
use Crasyhorse\PhpunitXrayReporter\Tags\TestInfo\TestType;
use Crasyhorse\PhpunitXrayReporter\Tags\Tests\Comment;
use Crasyhorse\PhpunitXrayReporter\Tags\Tests\Defects;
use Crasyhorse\PhpunitXrayReporter\Tags\Tests\Start;
use Crasyhorse\PhpunitXrayReporter\Tags\Tests\TestKey;
use Exception;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use PHPUnit\Runner\AfterIncompleteTestHook;
use PHPUnit\Runner\AfterRiskyTestHook;
use PHPUnit\Runner\AfterSkippedTestHook;
use PHPUnit\Runner\AfterSuccessfulTestHook;
use PHPUnit\Runner\AfterTestErrorHook;
use PHPUnit\Runner\AfterTestFailureHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\AfterTestWarningHook;
use PHPUnit\Runner\BeforeTestHook;
use ReflectionMethod;

/**
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class Extension implements BeforeTestHook, AfterSuccessfulTestHook, AfterTestFailureHook, AfterSkippedTestHook, AfterIncompleteTestHook, AfterTestWarningHook, AfterTestErrorHook, AfterRiskyTestHook, AfterTestHook
{
    public function executeBeforeTest(string $test): void
    {
        // Get startDate here
    }

    /**
     * @param class-string $test
     */
    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        $result = $this->parseDocBlock($test);
        var_dump($result);
    }

    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        throw new Exception('Not implemented yet.');
    }

    public function executeAfterTestError(string $test, string $message, float $time): void
    {
        throw new Exception('Not implemented yet.');
    }

    public function executeAfterTestWarning(string $test, string $message, float $time): void
    {
        throw new Exception('Not implemented yet.');
    }

    public function executeAfterSkippedTest(string $test, string $message, float $time): void
    {
        throw new Exception('Not implemented yet.');
    }

    public function executeAfterIncompleteTest(string $test, string $message, float $time): void
    {
        throw new Exception('Not implemented yet.');
    }

    public function executeAfterRiskyTest(string $test, string $message, float $time): void
    {
        throw new Exception('Not implemented yet.');
    }

    public function executeAfterTest(string $test, float $time): void
    {
    }

    /**
     * Parses the doc block of a test method.
     *
     * @param class-string $classMethod
     *
     * @return array
     */
    private function parseDocBlock(string $classMethod): array
    {
        $docBlock = (new ReflectionMethod($classMethod))->getDocComment();

        $customTags = [
            new TestExecutionKey(),
            // INFO
            new Project(),
            new Description(),
            new Project(),
            new Revision(),
            new Summary(),
            new TestEnvironments(),
            new TestPlanKey(),
            new User(),
            new Version(),
            // TESTS
            new Comment(),
            new Defects(),
            new TestKey(),
            // TESTINFO
            new Definition(),
            new Labels(),
            new ProjectKey(),
            new RequirementKeys(),
            new TestType(),
        ];

        $tags = PhpDocumentor::tags()->with($customTags);
        // $tags = PhpDocumentor::tags();

        $parser = new PhpdocParser($tags);

        return $parser->parse($docBlock);
    }
}
