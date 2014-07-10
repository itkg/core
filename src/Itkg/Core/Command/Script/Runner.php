<?php

namespace Itkg\Core\Command\Script;

use Doctrine\DBAL\Connection;
use Itkg\Core\Command\Script\Migration\Exception;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Runner implements RunnerInterface
{
    /**
     * Doctrine connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @var Migration
     */
    private $migration;

    /**
     * Constructor
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Run a migration
     *
     * @param Migration $migration
     * @return void
     * @throws \Exception
     * @throws Migration\Exception
     */
    public function run(Migration $migration, $forcedRollback = false)
    {
        $this->migration = $migration;

        try {
            $this->playQueries();
            if($forcedRollback) {
                $this->playRollbackQueries();
            }
        } catch(Exception $e) {
            $this->playRollbackQueries();

            throw $e;
        }
    }

    /**
     * Play Script queries
     *
     * @throws \Exception
     * @return void
     */
    private function playQueries()
    {
        foreach ($this->migration->getQueries() as $idx => $query) {
            try {
                $this->connection->executeQuery($query);
            } catch(\Exception $e) {
                // Only throw if more than one query is played
                if ($idx >= 1) {
                    throw new Exception($e->getMessage());
                }

                throw $e;
            }
        }
    }

    /**
     * Play rollback queries
     *
     * @return void
     */
    private function playRollbackQueries()
    {
        foreach ($this->migration->getRollbackQueries() as $query) {
            $this->connection->executeQuery($query);
        }
    }

} 