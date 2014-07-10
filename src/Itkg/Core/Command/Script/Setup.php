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
    private $rollbackedFirst;

    /**
     * Indicate that rollback is required
     *
     * @var bool
     */
    private $forcedRollback;

    /**
     * Constructor
     *
     * @param RunnerInterface $runner
     * @param LoaderInterface $loader
     * @param Migration\Factory $migrationFactory
     */
    public function __construct(RunnerInterface $runner, LoaderInterface $loader, Factory $migrationFactory)
    {
        $this->runner = $runner;
        $this->loader = $loader;
        $this->migrationFactory = $migrationFactory;
    }

    /**
     * Create a migration from SQL script & rollback script
     *
     * @param $script
     * @param $rollbackScript
     * @throws \InvalidArgumentException
     */
    public function createMigration($script, $rollbackScript)
    {
        if(!file_exists($script) || !file_exists($rollbackScript)) {
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
            $queries = $this->loader->load($rollbackScript)->getQueries();
            $rollbackQueries = $this->loader->load($script)->getQueries();
        } else {
            $queries = $this->loader->load($script)->getQueries();
            $rollbackQueries = $this->loader->load($rollbackScript)->getQueries();
        }
        $this->migrations[] = $this->migrationFactory->createMigration($queries, $rollbackQueries);
    }

    /**
     * Run migrations
     */
    public function run()
    {
        try {
            foreach ($this->migrations as $migration) {
                $this->runner->run($migration, $this->forcedRollback);
            }
            $this->displayQueries($this->runner->getPlayedQueries());
        } catch(\Exception $e) {
            $this->displayQueries($this->runner->getPlayedQueries());
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

    /**
     * Display all queries
     *
     * @param array $queries
     */
    public function displayQueries(array $queries)
    {
        foreach($queries as $query) {
            echo sprintf('%s;%s', $query, PHP_EOL);
        }
    }
}
