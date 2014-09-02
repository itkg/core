<?php

namespace Itkg\Core\Event;

use Itkg\Core\ServiceContainer;
use Symfony\Component\EventDispatcher\Event;

final class KernelEvents extends Event
{
    const CONFIG_LOADED = 'kernel.config.loaded';
    const APP_LOADED = 'kernel.app.loaded';

    /**
     * @var ServiceContainer
     */
    private $container;

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
