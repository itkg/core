<?php

namespace Itkg\Core\Command\DatabaseUpdate;

use Itkg\Core\Migration;

/**
 * Interface RunnerInterface
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface RunnerInterface
{
    /**
     * Run a migration
     *
     * @param Migration $migration
     * @param bool      $executeQueries
     * @param bool      $forcedRollback
     *
     * @return void
     */
    public function run(Migration $migration, $executeQueries = false, $forcedRollback = false);

    /**
     * Get played queries
     *
     * @return array
     */
    public function getPlayedQueries();
}
