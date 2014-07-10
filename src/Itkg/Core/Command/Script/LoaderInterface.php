<?php

namespace Itkg\Core\Command\Script;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
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