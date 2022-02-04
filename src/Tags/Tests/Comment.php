<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\DescriptionTag;

class Comment extends DescriptionTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-comment');
    }
}
