<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Types;

/**
 * Identifies a class that represents an XRay-JSON type.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
interface XrayType
{
    public function jsonSerialize();
}
