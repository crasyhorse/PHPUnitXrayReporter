<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Parser;

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
use Crasyhorse\PhpunitXrayReporter\Tags\Tests\TestKey;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use ReflectionMethod;

/**
 * Encapsulates jasny/phpdoc-parser.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class Parser
{
    /**
     * @var array<int,string>
     */
    private $customTags;

    /**
     * List the tags that are allowed to be inserted into the parser result.
     *
     * @var array
     */
    private $allowedTags;

    /**
     * @param array<int,string> $additionalCustomTags
     */
    public function __construct(array $additionalCustomTags = null)
    {
        $this->customTags = [
            new TestExecutionKey(),
            new Project(),
            new Description(),
            new Project(),
            new Revision(),
            new Summary(),
            new TestEnvironments(),
            new TestPlanKey(),
            new User(),
            new Version(),
            new Comment(),
            new Defects(),
            new TestKey(),
            new Definition(),
            new Labels(),
            new ProjectKey(),
            new RequirementKeys(),
            new TestType(),
        ];

        if (is_array($additionalCustomTags)) {
            $this->customTags = array_merge($this->customTags, $additionalCustomTags);
        }

        $this->allowedTags = [];
    }

    /**
     * Parses the doc block of a method.
     *
     * @return array
     */
    final public function parse(string $test): array
    {
        $testName = $this->sanitize($test);
        $docBlock = (new ReflectionMethod($testName))->getDocComment();
        $tags = PhpDocumentor::tags()->with($this->customTags);

        $parser = new PhpdocParser($tags);

        $metaInformation = $parser->parse($docBlock);

        return $metaInformation;
    }

    /**
     * Takes the test name and strips off namespace, class name and eventually
     * existing "with data set" strings.
     *
     * @return string
     */
    final private function sanitize(string $test): string
    {
        $testName = $this->stripOffWithDataSet($test);

        return $testName;
    }

    /**
     * Strips off the "with data set ..." string from the test name.
     *
     * @return string
     */
    final private function stripOffWithDataSet(string $test): string
    {
        preg_match_all('/([[:alpha:]][_0-9a-zA-Z:\\\]+)(?!< with data set)/', $test, $matches);
        $testName = $matches[0][0];

        return $testName;
    }
}
