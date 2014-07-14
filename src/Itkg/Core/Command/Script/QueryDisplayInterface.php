<?php

namespace Itkg\Core\Command\Script;

interface QueryDisplayInterface
{
    /**
     * Display a query
     *
     * @param string $query
     */
    public function display($query);
} 