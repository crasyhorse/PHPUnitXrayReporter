<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Jasny\PhpdocParser\Tag\ArrayTag;

class RequirementKeys extends ArrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-requirementKeys', 'string');
    }
}
