<?php

namespace Itkg\Core\Command\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Itkg\Core\Command\Model\Migration\Factory;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Setup
{
    /**
     * @var array
     */
    protected $migrations = array();

    /**
     * Script runner
     *
     * @var RunnerInterface
     */
    protected $runner;

    /**
     * Script loader
     *
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * Migration factory
     *
     * @var Factory
     */
    protected $migrationFactory;

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
    }

    /**
     * Create a migration from SQL script & rollback script
     *
     * @param $script
     * @param $rollbackScript
     */
    public function createMigration($script, $rollbackScript)
    {
        $queries = $this->loader->load($script)->getQueries();
        $rollbackQueries = $this->loader->load($rollbackScript)->getQueries();

        $this->migrations[] = $this->migrationFactory->createMigration($queries, $rollbackQueries);
    }

    /**
     * Run migrations
     */
    public function run()
    {
        foreach ($this->migrations as $migration) {
            $this->runner->run($migration);
        }
    }
}
