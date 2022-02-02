<?php

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Jasny\PhpdocParser\Tag\DescriptionTag;

class Summary extends DescriptionTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-summary', '');
    }
}
