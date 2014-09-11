<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

/**
 * Interface QueryFormatterInterface
 *
 * @package Itkg\Core\Command\DatabaseUpdate\Query
 */
interface FormatterInterface
{
    /**
     * Format a query
     *
     * @param string $query
     * @return string
     */
    public function format($query);
}
