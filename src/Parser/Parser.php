<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Parser;

use Crasyhorse\PhpunitXrayReporter\Reporter\Results\TestResult;
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
     * @param array<array-key, string>  $whitelistedTags      Allow additional tags like @test or @dataProvider
     * @param array<array-key, string>  $blacklistedTags      Remove tags like @param or @return
     * @param array<array-key, XrayTag> $additionalCustomTags Additional Tags of type XrayTag
     */
    public function __construct(array $whitelistedTags = [], array $blacklistedTags = [], array $additionalCustomTags = [])
    {
        $tagSet = new TagSet($whitelistedTags, $blacklistedTags);
        $this->customTags = $tagSet->getCustomTags($additionalCustomTags);
    }

    /**
     * Parses the doc block of a method.
     *
     * @return array
     */
    final public function parse(TestResult $result): array
    {
        $testName = $this->stripOffWithDataSet($result->getTest());
        $docBlock = (new ReflectionMethod($testName))->getDocComment();
        $tags = PhpDocumentor::tags()->with($this->customTags);
        $parser = new PhpdocParser($tags);

        $meta = $parser->parse($docBlock);
        $meta['start'] = $result->getStart();
        $meta['finish'] = $result->getFinish();
        $meta['comment'] = $result->getMessage() ?? 'Test has passed.';

        return $meta;
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
