<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Tests;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\ArrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType Defects.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class Defects extends ArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-defects', 'string');
    }
}
