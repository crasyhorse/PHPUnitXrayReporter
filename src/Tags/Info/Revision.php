<?php

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Jasny\PhpdocParser\Tag\WordTag;

class Revision extends WordTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-revision', '');
    }
}
