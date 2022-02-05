<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\DescriptionTag;

/**
 * Represents the XrayTag that correlats with the XrayType Description.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Description extends DescriptionTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-description');
    }
}
