<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Jasny\PhpdocParser\Tag\WordTag;

class ProjectKey extends WordTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-projectKey', '');
    }
}
