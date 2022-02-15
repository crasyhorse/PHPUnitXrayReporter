<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags;

use Jasny\PhpdocParser\Tag\AbstractTag;

class ModifiedDescriptionTag extends AbstractTag {

    public function process(array $notations, string $value): array
    {

        $notations[$this->name] = $this->stripOffClosingDocBlockComment($value);

        return $notations;
    }

    /**
     * Removes the closing PHPDoc comment consisting of an asterisk and a slash.
     * To ensure that also multiline strings are possible, multiple regex 
     * are handle afterwards, if the one before found nothing
     * 1) Is there any string with following *\/
     * 2) Is there any string with following * @ (for another annotation)
     * 3) Is there any string with following $ for the last possible case
     *    this method can become
     *
     * @param string $value
     *
     * @return string|string[]|null
     * @psalm-suppress PossiblyInvalidArgument 
     */
    protected function stripOffClosingDocBlockComment($value)
    {
        $matched = preg_match('/([^\/]*)(?=\*\/$)/', $value, $matches);
        if (!$matched) {
            $matched = preg_match('/([^\/]*)(?=\*[ ]*$)/', $value, $matches);
        }
        if (!$matched) {
            preg_match('/([^\/]*)(?=$)/', $value, $matches);
        }
        
        $matches[0] = trim(str_replace('* ', "\n", $matches[0]));
        
        return $matches[0];
    }

}