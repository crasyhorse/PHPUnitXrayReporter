<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Unit;

use Carbon\Carbon;
use CrasyHorse\PhpunitXrayReporter\Reporter\Reporter;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\FailedTest;
use CrasyHorse\PhpunitXrayReporter\Reporter\Results\SuccessfulTest;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * @author Florian Weidinger
 *
 * @since 0.1.0
 */
class ReporterTest extends TestCase
{
    /**
     * @var string
     */
    protected $configDir;

    /**
     * @var string
     */
    protected $outputDir;

    public function setUp(): void
    {
        $this->configDir = '.';
        foreach (['tests', 'Assets', 'xray-reporterrc_parsertest_with_info.json'] as $pathPart) {
            $this->configDir = $this->configDir.DIRECTORY_SEPARATOR.$pathPart;
        }
        $this->outputDir = '.';
        foreach (['tests', 'Outputs'] as $pathPart) {
            $this->outputDir = $this->outputDir.DIRECTORY_SEPARATOR.$pathPart;
        }
    }

    /**
     * @test
     * @group Reporter
     * @dataProvider no_testKey_data_provider
     */
    public function reporter_gets_right_amount_of_test_objects_while_testKey_not_given($resultList, $expected): void
    {
        $reporter = new Reporter($this->outputDir, $this->configDir);

        foreach ($resultList as $result) {
            $reporter->add($result);
        }
        $reporter->processResults();

        $actual = file_get_contents($this->outputDir.DIRECTORY_SEPARATOR.'newExecution.json');
        unlink($this->outputDir.DIRECTORY_SEPARATOR.'newExecution.json');

        $this->assertEquals($expected, $actual);
    }

    public function no_testKey_data_provider()
    {
        $start = Carbon::now(new DateTimeZone('Europe/Berlin'));
        $time = 0;

        return [
            'Test 1' => [
                [
                    new FailedTest('CrasyHorse\Tests\Assets\PseudoSpec::spec8', $time, $start, 'Failed asserting 2+4=6.'),
                    new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec9', $time, $start),
                    new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec10 with dataprovider 1', $time, $start),
                    new FailedTest('CrasyHorse\Tests\Assets\PseudoSpec::spec10 with dataprovider 2', $time, $start, 'Failed for my country'),
                    new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec10 with dataprovider 3', $time, $start),
                    new SuccessfulTest('CrasyHorse\Tests\Assets\PseudoSpec::spec11', $time, $start),
                ],
                    "{\n".
                    "    \"info\": {\n".
                    "        \"project\": \"DEMO\",\n".
                    "        \"summary\": \"example-config\",\n".
                    "        \"description\": \"This is an example for description\",\n".
                    "        \"version\": \"0.3.0\",\n".
                    "        \"revision\": \"0.3.0\",\n".
                    "        \"user\": \"Botuser\",\n".
                    "        \"testEnvironments\": [\n".
                    "            \"IOS\",\n".
                    "            \"Android\"\n".
                    "        ]\n".
                    "    },\n".
                    "    \"tests\": [\n".
                    "        {\n".
                    '            "start": "'.$start->toIso8601String()."\",\n".
                    '            "finish": "'.$start->toIso8601String()."\",\n".
                    "            \"comment\": \"Failed asserting 2+4=6.\",\n".
                    "            \"status\": \"FAIL\",\n".
                    "            \"testInfo\": {\n".
                    "                \"projectKey\": \"DEMO\",\n".
                    "                \"summary\": \"spec8\",\n".
                    "                \"testType\": \"Generic\",\n".
                    "                \"definition\": \"spec8\"\n".
                    "            }\n".
                    "        },\n".
                    "        {\n".
                    '            "start": "'.$start->toIso8601String()."\",\n".
                    '            "finish": "'.$start->toIso8601String()."\",\n".
                    "            \"comment\": \"Test has passed.\",\n".
                    "            \"status\": \"PASS\",\n".
                    "            \"testInfo\": {\n".
                    "                \"projectKey\": \"DEMO\",\n".
                    "                \"summary\": \"spec9\",\n".
                    "                \"testType\": \"Generic\",\n".
                    "                \"definition\": \"spec9\"\n".
                    "            }\n".
                    "        },\n".
                    "        {\n".
                    '            "start": "'.$start->toIso8601String()."\",\n".
                    '            "finish": "'.$start->toIso8601String()."\",\n".
                    "            \"comment\": \"Failed for my country\",\n".
                    "            \"status\": \"FAIL\",\n".
                    "            \"testInfo\": {\n".
                    "                \"projectKey\": \"DEMO\",\n".
                    "                \"summary\": \"spec10\",\n".
                    "                \"testType\": \"Generic\",\n".
                    "                \"definition\": \"spec10\"\n".
                    "            }\n".
                    "        },\n".
                    "        {\n".
                    '            "start": "'.$start->toIso8601String()."\",\n".
                    '            "finish": "'.$start->toIso8601String()."\",\n".
                    "            \"comment\": \"Test has passed.\",\n".
                    "            \"status\": \"PASS\",\n".
                    "            \"testInfo\": {\n".
                    "                \"projectKey\": \"DEMO\",\n".
                    "                \"summary\": \"spec11\",\n".
                    "                \"testType\": \"Generic\",\n".
                    "                \"definition\": \"spec11\"\n".
                    "            }\n".
                    "        }\n".
                    "    ]\n".
                    '}',
            ],
        ];
    }
}
