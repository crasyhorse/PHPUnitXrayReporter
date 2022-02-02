<?php

namespace Crasyhorse\PhpunitXrayReporter\Tags;

use Jasny\PhpdocParser\Tag\WordTag;

class TestExecutionKey extends WordTag
{
    public function __construct()
    {
        parent::__construct('XRAY-testExecutionKey', '');
    }
}
