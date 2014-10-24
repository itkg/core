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
 * Interface LoaderInterface
 *
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
