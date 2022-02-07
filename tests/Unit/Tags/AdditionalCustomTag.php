<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Unit\Tags;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\DescriptionTag;

/**
 * Additional custom tag for testing purposes.
 *
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class AdditionalCustomTag extends DescriptionTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-ADDITIONAL-custom');
    }
}
