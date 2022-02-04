<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\WordTag;

class TestKey extends WordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-testKey', '');
    }
}
