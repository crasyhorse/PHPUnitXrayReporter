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
        preg_match_all('/(?=\s*)([^@\s\/*][^\r\n]*)/', $value, $matches);

        if (array_key_exists(0, $matches[0]))
        {
            $notations['summary'] = $matches[0][0];

            if (array_key_exists(1, $matches[0]))
            {
                $notations['description'] = $matches[0][1];
            } else {
                $notations['description'] = $matches[0][0];
            }
        }
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
