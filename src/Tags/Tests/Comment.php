<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Jasny\PhpdocParser\Tag\DescriptionTag;

class Comment extends DescriptionTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-comment');
    }
}
