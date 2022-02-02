<?php

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Jasny\PhpdocParser\Tag\CustomTag;

class Start extends CustomTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-start', '');
    }
}
