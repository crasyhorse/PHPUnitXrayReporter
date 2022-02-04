<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Jasny\PhpdocParser\Tag\WordTag;

class TestKey extends WordTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-testKey', '');
    }
}
