<?php

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Jasny\PhpdocParser\Tag\ArrayTag;

class Defects extends ArrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-defects', '');
    }
}
