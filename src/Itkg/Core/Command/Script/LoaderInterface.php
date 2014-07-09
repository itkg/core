<?php

namespace Itkg\Core\Command\Model;

interface LoaderInterface
{
    /**
     * Load a script file
     *
     * @param string $script
     * @return LoaderInterface
     */
    public function load($script);

    /**
     * Get SQL queries
     *
     * @return array
     */
    public function getQueries();
} 