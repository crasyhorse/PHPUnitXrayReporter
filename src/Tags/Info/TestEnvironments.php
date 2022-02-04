<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\ArrayTag;

class TestEnvironments extends ArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-testEnvironments', 'string');
    }
}
