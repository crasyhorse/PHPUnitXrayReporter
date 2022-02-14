<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags\Tests;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\ModifiedDescriptionTag;
use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;

/**
 * Represents the XrayTag that correlates with the XrayType Defects.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class Comment extends ModifiedDescriptionTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-TESTS-comment', 'string');
    }
}
