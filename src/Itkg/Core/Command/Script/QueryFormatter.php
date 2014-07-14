<?php

namespace Itkg\Core\Command\Script;

class QueryFormatter implements QueryFormatterInterface
{

    /**
     * Format a query
     *
     * @param string $query
     * @return string
     */
    public function format($query)
    {
        return sprintf('%s;%s', $query, PHP_EOL);
    }
}