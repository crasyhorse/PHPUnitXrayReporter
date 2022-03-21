<?php

namespace CrasyHorse\PhpunitXrayReporter\Exceptions;

use Exception;
use Throwable;

/**
 * This exception is thrown if the configuration file could not be found.
 *
 * @author Florian Weidinger
 * @since 0.2.0
 */
class InvalidArgumentException extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
