<?php

namespace Itkg\Core\Command\Script;

interface LocatorInterface
{
    /**
     * Get all scripts for a path
     *
     * @return array
     */
    public function findScripts();

    /**
     *
     * Get all rollbakc scripts for a path
     *
     * @return array
     */
    public function findRollbackScripts();

    /**
     * Set params
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params = array());
} 