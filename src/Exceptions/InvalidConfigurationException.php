<?php

namespace CrasyHorse\PhpunitXrayReporter\Exceptions;

use Exception;
use Throwable;

/**
 * This exception is thrown if the configuration object is malformed.
 *
 * @author Florian Weidinger
 * @since 0.1.0
 */
class InvalidConfigurationException extends Exception
{
    public function __construct(array $messages, int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Your configuration object is malformed. Please check it!', $code, $previous);

        /** @var array<array-key, string> $messages */
        $this->message = implode(PHP_EOL, array_merge([parent::getMessage()], $messages));
    }
}
