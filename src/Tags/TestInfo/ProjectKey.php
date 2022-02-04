<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\WordTag;

class ProjectKey extends WordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-projectKey', '');
    }
}
