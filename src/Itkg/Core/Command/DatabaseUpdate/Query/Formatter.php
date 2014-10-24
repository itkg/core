<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate\Query;

/**
 * Class QueryFormatter
 *
 * Format query
 *
 * @package Itkg\Core\Command\DatabaseUpdate\Query
 */
class Formatter
{

    /**
     * Format a query
     *
     * @param string $query
     * @return string
     */
    public function format($query)
    {
        return sprintf('%s;%s', str_replace(array("\n", "\r"), '', $query), PHP_EOL);
    }
}
