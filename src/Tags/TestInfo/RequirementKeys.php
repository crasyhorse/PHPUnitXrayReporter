<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\ArrayTag;

class RequirementKeys extends ArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-requirementKeys', 'string');
    }
}
