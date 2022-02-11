<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Feature;

use Opis\JsonSchema\Helper;
use Opis\JsonSchema\Validator;
use PHPUnit\Framework\TestCase;

/**
 * @author Felix Franke
 *
 * @since 0.1.0
 */
class ValidateReportFilesTest extends TestCase
{
    protected $validator;

    protected $validationSchemeUrl = 'https://validation.test/crasyhorse/xraySchema.json';
    protected $XRAYFilesDirectory;

    protected function setup(): void
    {
        $this->XRAYFilesDirectory = dirname(__DIR__, 1).DIRECTORY_SEPARATOR.'XRAYFiles';

        $this->validator = new Validator();
        $resolver = $this->validator->resolver();

        //__DIR__.DIRECTORY_SEPARATOR.'xraySchema.json'
        /* @var \Opis\JsonSchema\Resolvers\SchemaResolver $resolver */
        $resolver->registerFile(
            $this->validationSchemeUrl,
            __DIR__.DIRECTORY_SEPARATOR.'xraySchema.json'
        );
        // var_dump($resolver);
    }

    public function validate($jsonfilename): bool
    {
        $filecontent = file_get_contents($jsonfilename);

        return $result = $this->validator->validate(
            Helper::toJSON($filecontent),
            $this->validationSchemeUrl
        );
    }

    /**
     * @test
     * @group Validation
     */
    public function created_json_files_are_correct(): void
    {
        $filelist = $this->getXrayJsonFiles();

        foreach ($filelist as $item) {
            $actual = $this->validate($this->XRAYFilesDirectory.DIRECTORY_SEPARATOR.$item);
            $this->assertEquals($actual, true);
        }
    }

    private function getXrayJsonFiles(): array
    {
        $filelist = scandir($this->XRAYFilesDirectory);
        //remove . and ..
        unset($filelist[0]);
        unset($filelist[1]);

        return $filelist;
    }
}
