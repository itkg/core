<?php

namespace Itkg\Core\Command\Script;

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