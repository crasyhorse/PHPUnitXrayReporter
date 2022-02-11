<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Parser;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestExecutionKey;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo\Definition;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo\Labels;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo\ProjectKey;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo\RequirementKeys;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo\TestType;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\Tests\Comment;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\Tests\Defects;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\Tests\TestKey;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

/**
 * Manages the list of available customs tags and also provides the list
 * of tags that should be present in the parser result.
 *
 * It provides methods to maintain a white list and a black list of tags,
 * so that the developer has the possibility to exactly define which tags
 * to be present in the result of the Parser class.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
final class TagSet
{
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
     * List of tags that must be present in the parsed result.
     *
     * @var array<array-key, string>
     */
    private $whitelistedTags;

    /**
     * @param array<array-key, string> $whitelistedTags List of tags that must be present in the parsed result.
     * @param array<array-key, string> $blacklistedTags Remove tags like @param or @return
     */
    public function __construct(array $whitelistedTags = [], array $blacklistedTags = [])
    {
        $this->blacklistedTags = $blacklistedTags;
        $this->whitelistedTags = empty($whitelistedTags) ? $this->getTagList() : $whitelistedTags;

        $this->allowedTags = $this->setAllowedTags();
    }

    /**
     * Returns the list of custom tags.
     *
     * By default there is a list of 18 predefined Xray tags but the developer may append
     * additional custom tags if request.
     *
     * @param array<array-key, XrayTag> $additionalCustomTags Additional Tags of type XrayTag
     *
     * @return array<array-key, XrayTag>
     */
    final public function getCustomTags(array $additionalCustomTags = []): array
    {
        return array_merge([
            new TestExecutionKey(),
            new Defects(),
            new TestKey(),
            new Definition(),
            new Labels(),
            new ProjectKey(),
            new RequirementKeys(),
            new TestType(),
            new Comment(),
        ], $additionalCustomTags);
    }

    /**
     * Returns the list of allowed tags.
     *
     * @return array<array-key, string>
     */
    final public function getAllowedTags(): array
    {
        return $this->allowedTags;
    }

    /**
     * Removes the list of black listed tags from the list of allowed tags.
     *
     * @param array<array-key, string> $allowedTags     The list of tags that should be present in the Parser classe's result.
     * @param array<array-key, string> $blacklistedTags Remove tags like @param or @return
     *
     * @return array<array-key, string>
     */
    private function applyBlackListToAllowedTags(array $allowedTags, array $blacklistedTags): array
    {
        return array_values(
                    array_diff($allowedTags, $blacklistedTags)
                );
    }

    /**
     * Prunes the list of allowed tags to show only those tags that are white listed.
     *
     * @param array<array-key, string> $allowedTags     The list of tags that should be present in the Parser classe's result.
     * @param array<array-key, string> $whitelistedTags Allow additional tags like @test or @dataProvider
     *
     * @return array<array-key, string>
     */
    private function applyWhiteListToAllowedTags(array $allowedTags, array $whitelistedTags): array
    {
        return array_intersect($whitelistedTags, $allowedTags);
    }

    /**
     * Returns the default list of allowed tags from Jasny\PhpdocParser\Set.
     *
     * @return array<array-key, string>
     */
    private function getTagList(): array
    {
        return [
            'api', 'author', 'copyright', 'deprecated', 'example', 'ignore', 'internal', 'link',
            'method', 'methods', 'package', 'param', 'params', 'property', 'property-read',
            'property-write', 'properties', 'return', 'see', 'since', 'throws', 'todo', 'uses',
            'used-by', 'var',
        ];
    }

    /**
     * Initializes the list of allowed tags and applies black and white list.
     *
     * @return array<array-key, string>
     */
    private function setAllowedTags(): array
    {
        return $this->applyBlackListToAllowedTags(
                    $this->applyWhiteListToAllowedTags(
                        $this->getTagList(), $this->whitelistedTags
                    ),
                    $this->blacklistedTags
                );
    }
}
