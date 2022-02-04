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
class Parser
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
     * @param class-string $classMethod
     *
     * @return array
     */
    public function parse($classMethod): array
    {
        $docBlock = (new ReflectionMethod($classMethod))->getDocComment();
        $tags = PhpDocumentor::tags()->with($this->customTags);

        $parser = new PhpdocParser($tags);

        $metaInformation = $parser->parse($docBlock);

        return $metaInformation;
    }
}
