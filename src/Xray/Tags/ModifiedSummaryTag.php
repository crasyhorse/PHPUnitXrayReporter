<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Tags;

use Jasny\PhpdocParser\Tag\Summery;

/**
 * Modifies original Summery class of Jasny\PhpdocParser.
 *
 * The problem was the handling of unannotated lines of the PHPunit doc block. All of this lines where
 * taken for the description, but we just want the first lines before the first annotation.
 * 
 * @author Paul Friedemann
 */
class ModifiedSummaryTag extends Summery
{
    /**
     * Process a notation.
     */
    public function process(array $notations, string $value): array
    {
        $value = $this->cutOfAllAfterAnnotation($value);

        preg_match_all('/(?=\s*)([^@\s\/*].*?)\n/m', $value, $matches);
        // preg_match_all('/^\s*(?:(?:\/\*)?\*\s*)?([^@\s\/*].*?)\r?$/m', $value, $matches, PREG_PATTERN_ORDER);

        // if (!isset($matches[1]) || $matches[1] === []) {
        //     return $notations;
        // }

        // $matches = $matches[1];

        $notations['summary'] = reset($matches[0]);
        $notations['description'] = implode("\n", $matches[0]);
        // $notations['summary'] = reset($matches);
        // $notations['description'] = implode("\n", $matches);

        return $notations;
    }

    /**
     * Removes all string lines after the first at-character.
     *
     * @param string $value
     *
     * @return string
     */
    protected function cutOfAllAfterAnnotation(string $value)
    {
        preg_match('/(?<=\/\*\*)([^@]*)/', $value, $matches);

        return $matches[0];
    }
}
