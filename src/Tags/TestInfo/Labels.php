<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Tags\ModifiedArrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType Labels.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Labels extends ModifiedArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-labels', 'string');
    }
}
