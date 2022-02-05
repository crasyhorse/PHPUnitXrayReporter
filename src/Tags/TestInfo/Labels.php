<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\ArrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType Labels.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Labels extends ArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-labels', 'string');
    }
}
