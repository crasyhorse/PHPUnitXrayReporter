<?php

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Jasny\PhpdocParser\Tag\DescriptionTag;

class Comment extends DescriptionTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-comment', '');
    }
}
