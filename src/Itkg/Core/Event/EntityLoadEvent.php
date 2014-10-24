<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     *
     * @param CacheableInterface $entity
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
