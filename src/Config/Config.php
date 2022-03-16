<?php

declare(strict_types=1);

namespace Crasyhorse\PhpunitXrayReporter\Config;

use Adbar\Dot;
use CrasyHorse\PhpunitXrayReporter\Exceptions\InvalidConfigurationException;
use CrasyHorse\PhpunitXrayReporter\Exceptions\InvalidArgumentException;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Helper;
use Opis\JsonSchema\Validator;

/**
 * This is the configuration object. It includes a json-schema for validation purposes.
 *
 * @author Paul Friedemann
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class Config
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @param string $configFileLocation Location of the configuration file. Has to be set via phpunit.xml.
     */
    public function __construct(string $configFileLocation)
    {
        $configuration = $this->readConfigurationFile($configFileLocation);
        // $this->configuration = $this->validate($configuration);
        $this->configuration = $configuration;
    }

    /**
     * Wrapper for Dot::get to return the configuration.
     *
     * @param string|null $name The name of the configuration object to return. If
     * no name is given, the complete configuration object will be returned.
     *
     * @return array|string|null
     */
    public function get(string $name = null)
    {
        $dot = new Dot($this->configuration);

        /** @var array|string|null */
        return $dot->get($name);
    }

    /**
     * Validates the configuration object.
     *
     * @param array $configuration
     *
     * @return array
     * @throws \CrasyHorse\PhpunitXrayReporter\Exceptions\InvalidConfigurationException
     */
    private function validate(array $configuration): array
    {
        $validator = new Validator();
        $resolver = $validator->resolver();

        /** @var \Opis\JsonSchema\Resolvers\SchemaResolver $resolver */
        $resolver->registerFile(
            'https://github.com/crasyhorse/PHPUnitXrayReporter/configSchema.json',
            __DIR__ . DIRECTORY_SEPARATOR . 'configSchema.json'
        );

        /** @var object $data */
        $data = Helper::toJSON($configuration);
        $result = $validator->validate(
            $data,
            'https://github.com/crasyhorse/PHPUnitXrayReporter/configSchema.json'
        );

        if (!$result->isValid()) {
            $formatter = new ErrorFormatter();

            /** @var \Opis\JsonSchema\Errors\ValidationError $validationErrors */
            $validationErrors = $result->error();
            $formattedValidationErrors = $formatter->formatFlat($validationErrors);

            throw new InvalidConfigurationException($formattedValidationErrors);
        }

        return $configuration;
    }

    /**
     * Reads the configuration file and returns the decoded array.
     *
     * @param string $configFileLocation Location of the configuration file. Has to be set via phpunit.xml.
     *
     * @return array
     * @throws \CrasyHorse\PhpunitXrayReporter\Exceptions\InvalidArgumentException
     */
    private function readConfigurationFile(string $configFileLocation): array
    {
        if (file_exists($configFileLocation)) {
            /** @var array */
            return json_decode(file_get_contents($configFileLocation), true);
        } else {
            throw new InvalidArgumentException('The needed config file could not be found on the given path: '.$configFileLocation);
        }
    }
}
