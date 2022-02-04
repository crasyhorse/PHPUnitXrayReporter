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
use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
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
     * @var array<array-key, XrayTag>
     */
    private $customTags;

    /**
     * List the tags that are allowed to be inserted into the parser result.
     *
     * @var array<array-key, string>
     */
    private $allowedTags;

    /**
     * List of tags that should not be present in the parsed result.
     *
     * @var array<array-key, string>
     */
    private $blacklistedTags;

    /**
     * @param array<array-key, XrayTag> $additionalCustomTags Additional Tags of type XrayTag
     * @param array<array-key, string>  $whitelistedTags      Allow additional tags like @test or @dataProvider
     * @param array<array-key, string>  $blacklistedTags      Remove tags like @param or @return
     */
    public function __construct(array $additionalCustomTags = null, array $whitelistedTags = null, array $blacklistedTags = null)
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

        $this->blacklistedTags = [
            'return',
            'param',
            'throws',
            'author',
            'since',
            'see',
        ];

        if (is_array($additionalCustomTags)) {
            $this->customTags = array_merge($this->customTags, $additionalCustomTags);
        }

        if (is_array($whitelistedTags)) {
            $this->allowedTags = array_merge($this->allowedTags, $whitelistedTags);
        }

        if (is_array($blacklistedTags)) {
            $this->allowedTags = array_diff($this->allowedTags, $blacklistedTags);
        }
    }

    /**
     * Parses the doc block of a method.
     *
     * @return array
     */
    final public function parse(string $test): array
    {
        $testName = $this->stripOffWithDataSet($test);
        $docBlock = (new ReflectionMethod($testName))->getDocComment();

        $tags = PhpDocumentor::tags()->with($this->customTags);
        $parser = new PhpdocParser($tags);

        $metaInformation = $parser->parse($docBlock);

        return $metaInformation;
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
