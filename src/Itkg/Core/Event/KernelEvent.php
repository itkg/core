<?php

namespace Itkg\Core\Event;

use Itkg\Core\ServiceContainer;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class KernelEvent
 *
 * Used by kernel
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class KernelEvent extends Event
{
    /**
     * @var ServiceContainer
     */
    private $container;

    /**
     * @param ServiceContainer $container
     */
    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @return ServiceContainer
     */
    public function getContainer()
    {
        return $this->container;
    }
}
