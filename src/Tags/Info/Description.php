<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\DescriptionTag;

class Description extends DescriptionTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-description');
    }
}
