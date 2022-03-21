<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Unit;

use CrasyHorse\PhpunitXrayReporter\Parser\TagSet;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\Description;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\Project;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\Revision;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\Summary;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\TestEnvironments;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\TestPlanKey;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\User;
// use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Info\Version;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestExecutionKey;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo\Definition;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo\Labels;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo\ProjectKey;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo\RequirementKeys;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo\Summary;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo\TestType;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Tests\Defects;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\Tests\TestKey;
use CrasyHorse\Tests\Unit\Tags\AdditionalCustomTag;
use PHPUnit\Framework\TestCase;

/**
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class TagSetTest extends TestCase
{
    /**
     * @test
     * @group TagSet
     * @dataProvider black_list_provider
     * @testdox getAllowedTags() returns the list of tages pruned by the black list: $_dataName
     */
    public function get_allowed_tags_returns_the_list_of_tags_pruned_by_the_given_black_list(array $blackList, array $expected): void
    {
        $tagSet = new TagSet([], $blackList);

        $actual = $tagSet->getAllowedTags();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @group TagSet
     * @dataProvider white_list_provider
     * @testdox getAllowedTags() returns the list of tags reduced to the requested white listed tags: $_dataName
     */
    public function get_allowed_tags_returns_the_list_of_white_listed_tags_(array $whiteList, array $expected): void
    {
        $tagSet = new TagSet($whiteList);

        $actual = $tagSet->getAllowedTags();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @group TagSet
     * @dataProvider black_and_white_list_provider
     * @testdox getAllowedTags() returns the list of predfined tags reduced by black and white list: $_dataName
     */
    public function get_allowed_tags_returns_the_list_of_predefined_tags_influenced_by_black_and_white_list(array $blackList, array $whiteList, array $expected): void
    {
        $tagSet = new TagSet($whiteList, $blackList);

        $actual = $tagSet->getAllowedTags();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @group TagSet
     */
    public function get_custom_tags_returns_the_list_of_predfined_tags(): void
    {
        $tagSet = new TagSet();

        $actual = $tagSet->getCustomTags();

        $expected = [
            new Defects(),
            new Definition(),
            new Labels(),
            new ProjectKey(),
            new RequirementKeys(),
            new Summary(),
            new TestExecutionKey(),
            new TestKey(),
            new TestType(),
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @group TagSet
     */
    public function get_custom_tags_returns_additional_custom_tags_if_requested(): void
    {
        $tagSet = new TagSet();

        $actual = $tagSet->getCustomTags([new AdditionalCustomTag()]);

        $expected = [
            new Defects(),
            new Definition(),
            new Labels(),
            new ProjectKey(),
            new RequirementKeys(),
            new Summary(),
            new TestExecutionKey(),
            new TestKey(),
            new TestType(),
            new AdditionalCustomTag(),
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * Provides black lists.
     *
     * @return array<array-key, string>
     */
    public function black_list_provider(): array
    {
        $blackList = $this->createList();

        return [
            'all tags because black list is empty' => [
                $blackList[0],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            "all tags but ['return', 'see', 'since']'" => [
                $blackList[1],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            'an empty list because all tags are blacklisted' => [
                $blackList[2],
                [],
            ],
            'an empty list because all tags plus additional tags are blacklisted' => [
                $blackList[3],
                [],
            ],
        ];
    }

    public function black_and_white_list_provider(): array
    {
        $blackList = $this->createList();
        $whiteList = $this->createList();

        return [
            'all tags because black and white list are both empty' => [
                $blackList[0],
                $whiteList[0],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            "['return', 'see', 'since'] because black list is empty and white list is set" => [
                $blackList[0],
                $whiteList[1],
                $whiteList[1],
            ],
            'all tags because black list is empty and white includes all tags' => [
                $blackList[0],
                $whiteList[2],
                $whiteList[2],
            ],
            'all tags because black list is empty and white includes all tags plus additional tags' => [
                $blackList[0],
                $whiteList[3],
                $whiteList[2],
            ],
            "all tags exclusive ['return', 'see', 'since'] because black list is set and white list is empty" => [
                $blackList[1],
                $whiteList[0],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            "an empty list because black and white list are both set to ['return', 'see', 'since']" => [
                $blackList[1],
                $whiteList[1],
                [],
            ],
            "all tags exclusive ['return', 'see', 'since'] because black list is set and white list is set to all tags" => [
                $blackList[1],
                $whiteList[2],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            "all tags exclusive ['return', 'see', 'since'] because black list is set and white list is set to all tags plus additional tags" => [
                $blackList[1],
                $whiteList[3],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            'an empty list because black list is set to all tags and white list is empty' => [
                $blackList[2],
                $whiteList[0],
                [],
            ],
            "an empty list because black list is set to all tags and white list is set to ['return', 'see', 'since']" => [
                $blackList[2],
                $whiteList[1],
                [],
            ],
            'an empty list because black and white list are both set to all tags' => [
                $blackList[2],
                $whiteList[2],
                [],
            ],
            'an empty list because black list is set to all tags white list is set to all tags plus additional tags' => [
                $blackList[2],
                $whiteList[3],
                [],
            ],
            'an empty list because black list is set to all tags plus additional tags and white list is empty' => [
                $blackList[3],
                $whiteList[0],
                [],
            ],
            "an empty list because black list is set to all tags plus additional tags and white list is set to ['return', 'see', 'since']" => [
                $blackList[3],
                $whiteList[1],
                [],
            ],
            'an empty list because black list is set to all tags plus additional tags and white list is set to all tags' => [
                $blackList[3],
                $whiteList[2],
                [],
            ],
            'an empty list because black and white list are both set to all tags plus additional tags' => [
                $blackList[3],
                $whiteList[3],
                [],
            ],
        ];
    }

    /**
     * Provides white lists.
     *
     * @return array<array-key, string>
     */
    public function white_list_provider(): array
    {
        $whiteList = $this->createList();

        return [
            'empty white list' => [
                $whiteList[0],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            "['return', 'see', 'since']'" => [
                $whiteList[1],
                ['return', 'see', 'since'],
            ],
            'all tags' => [
                $whiteList[2],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
            'all tags + additional tags' => [
                $whiteList[3],
                [
                    'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                    'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                    'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
                    'used-by', 'var',
                ],
            ],
        ];
    }

    /**
     * Returns an array used as black or white list.
     *
     * @return array
     */
    private function createList(): array
    {
        return [
            [],
            ['return', 'see', 'since'],
            [
                'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
                'used-by', 'var',
            ],
            [
                'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
                'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
                'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
                'used-by', 'var', 'loremIpsum', 'tag',
            ],
        ];
    }
}
