<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Jasny\PhpdocParser\Tag\ArrayTag;

class TestEnvironments extends ArrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-testEnvironments','string');
    }
}
