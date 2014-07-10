<?php

namespace Itkg\Core\Command\Script;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface RunnerInterface
{
    /**
     * Run a migration
     *
     * @param Migration $migration
     * @return mixed
     */
    public function run(Migration $migration, $forcedRollback = false);
}
