<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags;

use Jasny\PhpdocParser\Tag\AbstractTag;

class ModifiedDescriptionTag extends AbstractTag {

    public function process(array $notations, string $value): array
    {

        $notations[$this->name] = $this->stripOffClosingDocBlockComment($value);

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
        preg_match('/([^\*\/]*)(?!\*\/$)/', $value, $matches);

        return $matches[0];
    }

}