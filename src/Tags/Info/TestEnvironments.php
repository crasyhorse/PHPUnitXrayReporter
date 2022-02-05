<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\ArrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType TestEnvironment.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class TestEnvironments extends ArrayTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-testEnvironments', 'string');
    }
}
