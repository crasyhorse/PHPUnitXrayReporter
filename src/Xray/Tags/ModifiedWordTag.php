<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags;

use Jasny\PhpdocParser\Tag\WordTag;
use function Jasny\str_before;

class ModifiedWordTag extends WordTag
{
    /**
     * Must be overwritten because otherwise the word behind the Tag is fully taken
     * until a space is given. In this project this behavior is not desired. In comma
     * separated words, just the first one should be taken.
     *
     * @param array<array-key, mixed> $notations
     *
     * @return non-empty-array<array-key, array<array-key, mixed>|mixed>
     * @psalm-suppress PossiblyInvalidCast
     * @psalm-suppress PossiblyInvalidArgument
     */
    public function process(array $notations, string $value): array
    {
        if ($value === '') {
            $notations[$this->name] = $this->default;

            return $notations;
        }
        $matched = preg_match('/^(?|"((?:[^"]+|\\\\.)*)"|\'((?:[^\']+|\\\\.)*)\')/', $value, $matches);
        $quoted = in_array($value[0], ['"', '\''], true) && $matched;
            
        $word = $quoted ? $matches[1] : str_before($value, ' ');
        $word = $this->stripOffCommaSeparatedStrings($word);
        $notations[$this->name] = $word;

        return $notations;
    }

    /**
     * Removes the closing PHPDoc comment consisting of an asterisk and a slash.
     *
     * @param string $value
     *
     * @return string|string[]|null
     * @psalm-suppress PossiblyInvalidArgument
     */
    protected function stripOffCommaSeparatedStrings($value)
    {
        preg_match('/[^,]*/', $value, $matches);

        return $matches[0];
    }
}
