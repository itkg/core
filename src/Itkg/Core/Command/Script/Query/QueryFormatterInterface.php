<?php

namespace Itkg\Core\Command\Script\Query;

interface QueryFormatterInterface
{
    /**
     * Format a query
     *
     * @param string $query
     * @return string
     */
    public function format($query);
}
