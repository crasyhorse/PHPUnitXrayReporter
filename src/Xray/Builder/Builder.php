<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Xray\Builder;

use CrasyHorse\PhpunitXrayReporter\Xray\Types\XrayType;

/**
 * Defines the public interface of all Builder classes.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
interface Builder
{
    /**
     * Builds a class of type XrayType.
     *
     * @return XrayType
     */
    public function build();
}
