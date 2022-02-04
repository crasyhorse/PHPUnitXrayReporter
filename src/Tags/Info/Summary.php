<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Jasny\PhpdocParser\Tag\DescriptionTag;

class Summary extends DescriptionTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-summary');
    }
}
