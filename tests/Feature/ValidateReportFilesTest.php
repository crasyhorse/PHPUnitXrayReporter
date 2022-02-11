<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Feature;

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

        $validator = new Validator();
        $resolver = $validator->resolver();

        /* @var \Opis\JsonSchema\Resolvers\SchemaResolver $resolver */
        $resolver->registerFile(
            $this->validationSchemeUrl,
            __DIR__.DIRECTORY_SEPARATOR.'xraySchema.json'
        );
    }

    public function validate($jsonfilename)
    {
        $filecontent = file_get_contents($jsonfilename);

        $result = $validator->validate(
            $filecontent,
            $this->validationSchemeUrl
        );
    }

    /**
     * @test
     * @group Validation
     */
    public function created_json_files_are_correct(): void
    {
        var_dump($this->XRAYFilesDirectory);
        $this->getXrayJsonFiles();
        //get files

        //validate
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
