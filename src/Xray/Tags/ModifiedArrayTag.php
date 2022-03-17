<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Tags;

use Jasny\PhpdocParser\PhpdocException;
use Jasny\PhpdocParser\Tag\ArrayTag;

class ModifiedArrayTag extends ArrayTag
{
    /**
     * Must be overriden because otherwise the closing PHPDoc comment
     * consisting of an asterisk and a slash will show up in the list
     * of test environments if this tag is the last one in the doc block.
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
            $notations[$this->name] = [];

            return $notations;
        }

        $itemString = $this->stripParentheses($value);

        if (str_contains($itemString, '*')) {
            $itemString = $this->stripOffClosingDocBlockComment($itemString);
        }

        $items = $this->splitValue($itemString);

        try {
            $array = $this->toArray($items);
        } catch (PhpdocException $exception) {
            throw new PhpdocException(
                "Failed to parse '@{$this->name} {$value}': ".$exception->getMessage(),
                0,
                $exception
            );
        }

        $notations[$this->name] = $array;

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
    protected function stripOffClosingDocBlockComment($value)
    {
        preg_match('/([^\/]*)(?=\*)/', $value, $matches);

        $matches[0] = trim($matches[0]);

        return $matches[0];
    }
}
