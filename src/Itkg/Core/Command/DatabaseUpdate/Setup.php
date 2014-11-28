<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate;

use Itkg\Core\Command\DatabaseUpdate\Migration\Factory;
use Itkg\Core\Command\DatabaseUpdate\Query\DecoratorInterface;

/**
 * Class Setup
 *
 * Setup release migrations
 *
 * Use locator to find scripts,
 * Use loader to get queries
 * Use runner to play them
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
     * Script locator
     *
     * @var LocatorInterface
     */
    private $locator;

    /**
     * Queries decorator
     * @var DecoratorInterface
     */
    private $decorator;

    /**
     * @var ReleaseChecker
     */
    private $releaseChecker;

    /**
     * Constructor
     *
     * @param RunnerInterface $runner
     * @param LoaderInterface $loader
     * @param Migration\Factory $migrationFactory
     * @param LocatorInterface $locator
     * @param Query\DecoratorInterface $decorator
     * @param ReleaseChecker $releaseChecker
     */
    public function __construct(
        RunnerInterface $runner,
        LoaderInterface $loader,
        Factory $migrationFactory,
        LocatorInterface $locator,
        DecoratorInterface $decorator,
        ReleaseChecker $releaseChecker
    ) {
        $this->runner           = $runner;
        $this->loader           = $loader;
        $this->migrationFactory = $migrationFactory;
        $this->locator          = $locator;
        $this->decorator        = $decorator;
        $this->releaseChecker   = $releaseChecker;
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
        $this->releaseChecker->checkScript($script, $rollbackScript);

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
     *
     * @return void
     *
     * @throws \RuntimeException
     * @throws \LogicException
     */
    private function createMigrations()
    {
        $scripts   = $this->locator->findScripts();
        $rollbacks = $this->locator->findRollbackScripts();

        $this->releaseChecker->checkScripts($scripts, $rollbacks);

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

        foreach ($this->migrations as $migration) {

            $this->runner->run($migration, $this->executeQueries, $this->forcedRollback);
        }

        // After run, we add decorated queries
        return $this->decorator->decorateAll($this->runner->getPlayedQueries());
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
