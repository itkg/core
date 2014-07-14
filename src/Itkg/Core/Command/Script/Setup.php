<?php

namespace Itkg\Core\Command\Script;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Itkg\Core\Command\Script\Migration\Factory;

/**
 * Setup release migrations
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Setup
{
    /**
     * @var array
     */
    private $migrations = array();

    /**
     * Script runner
     *
     * @var RunnerInterface
     */
    private $runner;

    /**
     * Script loader
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     * Migration factory
     *
     * @var Factory
     */
    private $migrationFactory;

    /**
     * Indicate that rollback is needed before script
     *
     * @var bool
     */
    private $rollbackedFirst = false;

    /**
     * Indicate that rollback is required
     *
     * @var bool
     */
    private $forcedRollback = false;

    /**
     * Execute queries or not
     * @var bool
     */
    private $executeQueries = false;

    /**
     * Loaded queries
     *
     * @var array
     */
    private $queries = array();

    /**
     * Constructor
     *
     * @param RunnerInterface $runner
     * @param LoaderInterface $loader
     * @param Migration\Factory $migrationFactory
     */
    public function __construct(RunnerInterface $runner, LoaderInterface $loader, Factory $migrationFactory)
    {
        $this->runner           = $runner;
        $this->loader           = $loader;
        $this->migrationFactory = $migrationFactory;
    }

    /**
     * Create a migration from SQL script & rollback script
     *
     * @param $script
     * @param $rollbackScript
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function createMigration($script, $rollbackScript)
    {
        if (!file_exists($script) || !file_exists($rollbackScript)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "%s or %s does not exist",
                    $script,
                    $rollbackScript
                )
            );
        }

        if ($this->rollbackedFirst) {
            /* When rollback is needed first we invert script & rollback script */
            $queries         = $this->loader->load($rollbackScript)->getQueries();
            $rollbackQueries = $this->loader->load($script)->getQueries();
        } else {
            $queries         = $this->loader->load($script)->getQueries();
            $rollbackQueries = $this->loader->load($rollbackScript)->getQueries();
        }
        $this->migrations[] = $this->migrationFactory->createMigration($queries, $rollbackQueries);

        return $this;
    }

    /**
     * Run migrations
     */
    public function run()
    {
        try {
            foreach ($this->migrations as $migration) {
                $this->runner->run($migration, $this->executeQueries, $this->forcedRollback);
            }
            $this->queries = array_merge($this->queries, $this->runner->getPlayedQueries());
        } catch (\Exception $e) {
            $this->queries = array_merge($this->queries, $this->runner->getPlayedQueries());

            throw $e;
        }
    }

    /**
     * @param boolean $forcedRollback
     * @return $this
     */
    public function setForcedRollback($forcedRollback)
    {
        $this->forcedRollback = $forcedRollback;

        return $this;
    }

    /**
     * @param $executeQueries
     * @return $this
     */
    public function setExecuteQueries($executeQueries)
    {
        $this->executeQueries = $executeQueries;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getForcedRollback()
    {
        return $this->forcedRollback;
    }

    /**
     * @param boolean $rollbackedFirst
     * @return $this
     */
    public function setRollbackedFirst($rollbackedFirst)
    {
        $this->rollbackedFirst = $rollbackedFirst;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getRollbackedFirst()
    {
        return $this->rollbackedFirst;
    }

    public function getQueries()
    {
        return $this->queries;
    }
}
