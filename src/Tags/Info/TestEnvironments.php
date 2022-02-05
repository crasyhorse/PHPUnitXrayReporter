<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\PhpdocException;
use Jasny\PhpdocParser\Tag\ArrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType TestEnvironment.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class TestEnvironments extends ArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-testEnvironments', 'string');
    }

    /**
     * Must be overriden because otherwise the closing PHPDoc comment
     * consisting of an asterisk and a slash will show up in the list
     * of test environments if this tag is the last one in the doc block.
     *
     * @param array<array-key, string> $notations
     *
     * @return array<array-key, string>
     */
    public function process(array $notations, string $value): array
    {
        if ($value === '') {
            $notations[$this->name] = [];

            return $notations;
        }

        $itemString = $this->stripOffClosingDocBlockComment(
            $this->stripParentheses($value)
        );

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
     * @return string|string[]|null
     */
    protected function stripOffClosingDocBlockComment(string $value)
    {
        preg_match('/([^\*\/ ]*)(?!< \*\/$)/', $value, $matches);

        return $matches[0];
    }
}
