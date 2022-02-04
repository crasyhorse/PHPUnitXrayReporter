<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\WordTag;

class TestPlanKey extends WordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-testPlanKey', '');
    }
}
