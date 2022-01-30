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
     * This is a test.
     *
     * @test
     *
     * @return void
     */
    public function spec_name(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    }
}
