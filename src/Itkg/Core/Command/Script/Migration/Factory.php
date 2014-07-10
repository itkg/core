<?php

namespace Itkg\Core\Command\Script\Migration;

use Itkg\Core\Command\Script\Migration;

/**
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