<?php

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Jasny\PhpdocParser\Tag\DescriptionTag;

class Definition extends DescriptionTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-definition');
    }
}
