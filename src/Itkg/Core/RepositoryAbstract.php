<?php

namespace Itkg\Core;

use Itkg\Core\ConfigInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Itkg\Core\EntityAbstract;

abstract class RepositoryAbstract
{
    /**
     * DB instance
     * @var Pelican_Db
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
     * @param ConfigInterface $config
     * @param Pelican_Db $db
     * @param EventDispatcher $dispatcher
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
