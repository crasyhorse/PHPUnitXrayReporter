<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter;

use PHPUnit\Runner\AfterIncompleteTestHook;
use PHPUnit\Runner\AfterRiskyTestHook;
use PHPUnit\Runner\AfterSkippedTestHook;
use PHPUnit\Runner\AfterSuccessfulTestHook;
use PHPUnit\Runner\AfterTestErrorHook;
use PHPUnit\Runner\AfterTestFailureHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\AfterTestWarningHook;
use PHPUnit\Runner\BeforeTestHook;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use Exception;
use ReflectionMethod;
use RuntimeException;

/**
 *
 *
 * @author Florian Weidinger
 * @since 0.1.0
 */
final class Extension implements
    BeforeTestHook,
    AfterSuccessfulTestHook,
    AfterTestFailureHook,
    AfterSkippedTestHook,
    AfterIncompleteTestHook,
    AfterTestWarningHook,
    AfterTestErrorHook,
    AfterRiskyTestHook,
    AfterTestHook
{
    public function executeBeforeTest(string $test): void
    {
    }

    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        $this->parseDocBlock($test);
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
     * @return void
     */
    private function parseDocBlock(string $classMethod): array
    {
        $docBlock = (new ReflectionMethod($classMethod))->getDocComment();

        // $customTags = [
        //     new FlagTag('important')
        // ];
        // $tags = PhpDocumentor::tags()->with($customTags);
        $tags = PhpDocumentor::tags();

        $parser = new PhpdocParser($tags);
        return $parser->parse($docBlock);
    }
}
