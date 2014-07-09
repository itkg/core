<?php

namespace Itkg\Core\Command\Model;

interface RunnerInterface
{
    /**
     * Run a migration
     *
     * @param Migration $migration
     * @return mixed
     */
    public function run(Migration $migration);
}
