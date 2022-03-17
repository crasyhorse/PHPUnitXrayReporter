<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Tags\TestInfo;

use CrasyHorse\PhpunitXrayReporter\Xray\Tags\ModifiedSummaryTag;
use CrasyHorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

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
