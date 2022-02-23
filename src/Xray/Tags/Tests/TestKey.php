<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags\Tests;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\ModifiedWordTag;

/**
 * Represents the XrayTag that correlats with the XrayType TestKey.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class TestKey extends ModifiedWordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-testKey', '');
    }
}
