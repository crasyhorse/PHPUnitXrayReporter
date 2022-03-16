<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\ModifiedWordTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType TestType.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class TestType extends ModifiedWordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-testType', '');
    }
}
