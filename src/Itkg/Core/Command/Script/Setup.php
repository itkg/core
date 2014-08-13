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
     * Script locator
     *
     * @var LocatorInterface
     */
    private $locator;

    /**
     * Constructor
     *
     * @param RunnerInterface $runner
     * @param LoaderInterface $loader
     * @param Migration\Factory $migrationFactory
     * @param LocatorInterface $locator
     */
    public function __construct(RunnerInterface $runner, LoaderInterface $loader, Factory $migrationFactory, LocatorInterface $locator)
    {
        $this->runner           = $runner;
        $this->loader           = $loader;
        $this->migrationFactory = $migrationFactory;
        $this->locator          = $locator;
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
            list($rollbackScript, $script) = array($script, $rollbackScript);
        }

        $this->migrations[] = $this->migrationFactory->createMigration(
            $this->loader->load($script)->getQueries(),
            $this->loader->load($rollbackScript)->getQueries()
        );

        return $this;
    }

    /**
     * Create migrations from scripts & rollback files
     */
    private function createMigrations()
    {
        $scripts   = $this->locator->findScripts();
        $rollbacks = $this->locator->findRollbackScripts();

        if (empty($scripts)) {
            throw new \RuntimeException(sprintf('No scripts were found in release'));
        }

        if (sizeof(array_diff_key($scripts, $rollbacks)) != 0) {
            throw new \LogicException('Please provide as scripts files as rollbacks files with the same name');
        }

        foreach ($scripts as $k => $script) {
            $this->createMigration($script, $rollbacks[$k]);
        }
    }

    /**
     * Run migrations
     */
    public function run()
    {
        $this->createMigrations();

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

    /**
     * Get script locator
     *
     * @return LocatorInterface
     */
    public function getLocator()
    {
        return $this->locator;
    }
}
