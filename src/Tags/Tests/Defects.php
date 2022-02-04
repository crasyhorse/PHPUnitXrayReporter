<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\ArrayTag;

class Defects extends ArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-defects', 'string');
    }
}
