<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\XRAY\Tags\ModifiedWordTag;

/**
 * Represents the XrayTag that correlats with the XrayType ProjectKey.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class ProjectKey extends ModifiedWordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-projectKey', '');
    }
}
