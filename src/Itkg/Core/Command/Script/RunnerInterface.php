<?php

namespace Itkg\Core\Command\Script;

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
     * @param bool $forcedRollback
     */
    public function run(Migration $migration, $forcedRollback = false);

    /**
     * Get played queries
     * @return array
     */
    public function getPlayedQueries();
}
