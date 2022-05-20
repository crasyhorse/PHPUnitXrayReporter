<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Results;

use MabeEnum\Enum;

/**
 * Lists the different typs of TestResults know by PHP-Unit.
 *
 * @author Florian Weidinger
 * @psalm-immutable
 */
class ResulType extends Enum
{
    public const SUCCESSFUL = 0;
    public const FAILED = 1;
    public const TODO = 2;
}
