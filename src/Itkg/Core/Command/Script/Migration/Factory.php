<?php

namespace Itkg\Core\Command\Model\Migration;

use Itkg\Core\Command\Model\Migration;

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