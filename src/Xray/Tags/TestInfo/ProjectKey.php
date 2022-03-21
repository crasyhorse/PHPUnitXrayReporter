<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo;

use CrasyHorse\PhpunitXrayReporter\Xray\Tags\ModifiedWordTag;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

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
