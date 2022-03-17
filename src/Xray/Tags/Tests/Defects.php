<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Tags\Tests;

use CrasyHorse\PhpunitXrayReporter\Xray\Tags\ModifiedArrayTag;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType Defects.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class Defects extends ModifiedArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-defects', 'string');
    }
}
