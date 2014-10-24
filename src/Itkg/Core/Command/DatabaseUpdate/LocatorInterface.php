<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate;

/**
 * Interface LocatorInterface
 *
 * @package Itkg\Core\Command\DatabaseUpdate
 */
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
