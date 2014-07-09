<?php

namespace Itkg\Core\Command\Model;

use Doctrine\DBAL\Driver\Connection;

class Runner implements RunnerInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function run(Migration $migration)
    {

    }
} 