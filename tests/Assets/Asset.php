<?php

declare(strict_types=1);

namespace CrasyHorse\Tests\Assets;

/**
 * This class has different methods that help to test the PHP-Unit extention.
 *
 * @author Florian Weidinger
 * @since 0.1.0
 */
class Asset
{
    /**
     * Adds two number.
     *
     * @param int $oper1
     * @param int $oper2
     *
     * @return int
     */
    public function add(int $oper1, int $oper2): int
    {
        return $oper1 + $oper2;
    }
}
