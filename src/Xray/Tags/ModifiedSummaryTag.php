<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags;

use Jasny\PhpdocParser\Tag\Summery;

class ModifiedSummaryTag extends Summery
{
    /**
     * Process a notation.
     *
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        $value = $this->cutOfAllAfterAnnotation($value);

        preg_match_all('/^\s*(?:(?:\/\*)?\*\s*)?([^@\s\/*].*?)\r?$/m', $value, $matches, PREG_PATTERN_ORDER);

        if (!isset($matches[1]) || $matches[1] === []) {
            return $notations;
        }

        $matches = $matches[1];

        $notations['summary'] = reset($matches);
        $notations['description'] = implode("\n", $matches);

        return $notations;
    }

    private function cutOfAllAfterAnnotation($value): string
    {
        preg_match('/(?<=\/\*\*)([^@]*)/', $value, $matches);

        return $matches[0];
    }
}
