<?php

namespace Itkg\Core\Event;

use Itkg\Core\CacheableInterface;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Class EntityLoadEvent
 *
 * @package Itkg\Core\Event
 */
class EntityLoadEvent extends BaseEvent
{
    /**
     * Entity instance
     *
     * @var CacheableInterface
     */
    private $entity;

    /**
     * Constructor
     */
    public function __construct(CacheableInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Entity getter
     *
     * @return CacheableInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
