<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\WordTag;

/**
 * Represents the XrayTag that correlats with the XrayType TestKey.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class TestKey extends WordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-testKey', '');
    }
}
