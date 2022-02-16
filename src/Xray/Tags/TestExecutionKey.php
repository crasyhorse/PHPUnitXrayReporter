<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags;

/**
 * Represents the XrayTag that correlats with the XrayType TestExecutionKey.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class TestExecutionKey extends ModifiedWordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-testExecutionKey', '');
    }
}
