<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Feature;

use Opis\JsonSchema\Errors\ErrorFormatter;
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

    protected $XRAYFilesDirectory;
    protected $schema;

    protected function setup(): void
    {
        $this->XRAYFilesDirectory = dirname(__DIR__, 1).DIRECTORY_SEPARATOR.'XRAYFiles';
        $this->validator = new Validator();
        $this->schema = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'xraySchema.json');
    }

    public function validate($jsonfilename): bool
    {
        var_dump($jsonfilename);
        $jsoncontent = json_decode(file_get_contents($jsonfilename));

        $result = $this->validator->validate(
            $jsoncontent,
            $this->schema
        );
        if (!$result->isValid()) {
            $formatter = new ErrorFormatter();

            /** @var \Opis\JsonSchema\Errors\ValidationError $validationErrors */
            $validationErrors = $result->error();
            $formattedValidationErrors = $formatter->formatFlat($validationErrors);

            var_dump($formattedValidationErrors);

            return false;
        }

        return $result->isValid();
    }

    /**
     * @test
     * @group Validation
     * @dataProvider getXrayJsonFiles
     */
    public function created_json_files_are_correct($filename): void
    {
        $actual = $this->validate($this->XRAYFilesDirectory.DIRECTORY_SEPARATOR.$filename);
        $this->assertEquals(true, $actual, 'For file: '.$filename);
    }

    public function getXrayJsonFiles(): array
    {
        $this->XRAYFilesDirectory = dirname(__DIR__, 1).DIRECTORY_SEPARATOR.'XRAYFiles';
        $filelist = scandir($this->XRAYFilesDirectory);
        //remove . and ..
        unset($filelist[0]);
        unset($filelist[1]);

        $testdata = [];
        foreach ($filelist as $file) {
            array_push($testdata, [$file]);
        }

        return $testdata;
    }
}
