<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags\TestInfo;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\ModifiedSummaryTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

/**
 * Represents the XrayTag that correlats with the XrayType RequirementKey.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Summary extends ModifiedSummaryTag implements XrayTag
{
}
