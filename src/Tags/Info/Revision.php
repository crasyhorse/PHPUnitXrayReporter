<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\WordTag;

/**
 * Represents the XrayTag that correlats with the XrayType Revision.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Revision extends WordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-revision', '');
    }
}
