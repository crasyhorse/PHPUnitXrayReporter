<?php


declare(strict_types=1);

namespace CrasyHorse\Tests\Unit;

use PHPUnit\Framework\TestCase;
use CrasyHorse\Tests\Assets\Asset;

/**
 *
 *
 * @author Florian Weidinger
 * @since
 */
class ExtensionTest extends TestCase
{
    /**
     * Cypress - cur.view führt zur CUR Übersichtsseite.
     * 
     * * Laden der Seite"cur.view"
     * Ergebnis: Die Anwendung wechselt zur Komponente SZCurOverview
     *
     * @XRAY-testExecutionKey
     * 
     * @test
     */
    public function simple_spec(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    public function int_provider(): array
    {
        return [
            'a = 2, b = 2' => [
                2, 2
            ]
        ];
    }

    /**
     * This is a test.
     *
     * @test
     * @dataProvider int_provider
     * @return void
     */
    public function spec_with_simple_provider(int $a, int $b): void
    {
        $asset = new Asset();
        $expected = $a + $b;
        $actual = $asset->add($a, $b);
        $this->assertEquals($expected, $actual);
    }

    /**
     * This is a test.
     *
     * @test
     * @dataProvider int_provider
     * @testdox Hallo Welt
     * @return void
     */
    public function spec_with_simple_provider_using_testdox_tag(int $a, int $b): void
    {
        $asset = new Asset();
        $expected = $a + $b;
        $actual = $asset->add($a, $b);
        $this->assertEquals($expected, $actual);
    }
}
