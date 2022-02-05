<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Xray\Tags\Info;

use Crasyhorse\PhpunitXrayReporter\Xray\Tags\XrayTag;
use Jasny\PhpdocParser\Tag\WordTag;

/**
 * Represents the XrayTag that correlats with the XrayType Project.
 *
 * @author Paul Friedemann
 *
 * @since 0.1.0
 */
class Project extends WordTag implements XrayTag
{
    public function __construct()
    {
        parent::__construct('XRAY-INFO-project', '');
    }
}
