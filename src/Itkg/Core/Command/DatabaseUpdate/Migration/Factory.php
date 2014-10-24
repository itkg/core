<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate\Migration;

use Itkg\Core\Command\DatabaseUpdate\Migration;

/**
 * Class Factory
 *
 * A simple factory which create migrations
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Factory
{
    /**
     * Create a migration
     *
     * @param array $queries
     * @param array $rollbackQueries
     * @return Migration
     */
    public function createMigration(array $queries = array(), array $rollbackQueries = array())
    {
        return new Migration($queries, $rollbackQueries);
    }
}
