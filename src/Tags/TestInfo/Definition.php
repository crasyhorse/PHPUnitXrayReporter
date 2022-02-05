<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Tags\XrayTag;
use Crasyhorse\PhpunitXrayReporter\Tags\ModifiedDescriptionTag;

/**
 * Represents the XrayTag that correlats with the XrayType Definition.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Definition extends ModifiedDescriptionTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTINFO-definition');
    }
}
