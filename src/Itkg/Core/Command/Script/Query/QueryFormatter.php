<?php

namespace Itkg\Core\Command\Script\Query;

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
        return sprintf('%s;%s', str_replace(array("\n", "\r"), '', $query), PHP_EOL);
    }
}
