<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Jasny\PhpdocParser\Tag\WordTag;

class User extends WordTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-user', '');
    }
}
