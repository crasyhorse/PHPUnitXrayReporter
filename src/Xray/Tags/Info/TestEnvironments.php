<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\ModifiedArrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType TestEnvironment.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class TestEnvironments extends ModifiedArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-testEnvironments', 'string');
    }
}
