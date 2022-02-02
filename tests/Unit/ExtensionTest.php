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
     * @return void
     */
    public function spec_name(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }

    /**
     * This is a test.
     *
     * @test
     *
     * @return void
     */
    public function spec_name1(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }
}
