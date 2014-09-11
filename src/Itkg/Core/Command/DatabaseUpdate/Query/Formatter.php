<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

/**
 * Class QueryFormatter
 *
 * Format query
 *
 * @package Itkg\Core\Command\DatabaseUpdate\Query
 */
class Formatter implements FormatterInterface
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
