<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core;

use Itkg\Core\ConfigInterface;
use Itkg\Core\EntityAbstract;
use Itkg\Core\DatabaseInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class RepositoryAbstract
 * @package Itkg\Core
 */
abstract class RepositoryAbstract
{
    /**
     * DB instance
     * @var DatabaseInterface
     */
    protected $db;

    /**
     * Config instance
     * @var ConfigInterface
     */
    protected $config;

    /**
     * EventDispatcher instance
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Entity instance
     * @var EntityAbstract
     */
    protected $entity;

    /**
     * Constructor
     *
     * @param ConfigInterface   $config
     * @param DatabaseInterface $db
     * @param EventDispatcher   $dispatcher
     */
    public function __construct(
        ConfigInterface $config,
        \Pelican_Db $db,
        EventDispatcher $dispatcher
    )
    {
        $this->config = $config;
        $this->db = $db;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Entity setter
     *
     * @param  EntityAbstracct $entity
     *
     * @return $this
     */
    public function setEntity(EntityAbstract $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Entity getter
     *
     * @return EntityAbstract
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
