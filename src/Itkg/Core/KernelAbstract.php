<?php

namespace Itkg\Core;

use Itkg\Core\Event\KernelEvent;
use Itkg\Core\Event\KernelEvents;

abstract class KernelAbstract
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    // File paths
    const GLOBAL_CONFIG_FILE = '/Resources/Config/global.yml';
    const GLOBAL_ROUTING_FILE = '/Resources/Config/routing.yml';

    /**
     * @param ServiceContainer $container
     * @param ApplicationInterface $app
     */
    public function __construct(ServiceContainer $container, ApplicationInterface $app)
    {
        $this->container = $container;
        $this->container
            ->setApp($app)
            ->setConfig($app->getConfig());

        $this->container['core']['dispatcher']->dispatch(
            KernelEvents::APP_LOADED,
            new KernelEvent($container)
        );

        $this->loadConfig()
            ->loadRouting();
    }

    /**
     * Get container
     *
     * @return ServiceContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Load Config from config files and dispatch event
     *
     * @return $this
     */
    protected function loadConfig()
    {
        $this->container['app']->getConfig()->load($this->getConfigFiles());

        $this->container['core']['dispatcher']->dispatch(
            KernelEvents::CONFIG_LOADED,
            new KernelEvent($this->container)
        );

        return $this;
    }

    /**
     * Get an array of config Files
     *
     * @return array
     */
    protected function getConfigFiles()
    {
        return array(
            __DIR__ . self::GLOBAL_CONFIG_FILE,
        );
    }

    /**
     * Get an array of routing files
     *
     * @return array
     */
    protected function getRoutingFiles()
    {
        return array(
            __DIR__ . self::GLOBAL_ROUTING_FILE,
        );
    }

    /**
     * Load routing from routing files
     *
     * @return $this
     */
    abstract protected function loadRouting();

    /**
     * Create a response from a request
     *
     * @param $request
     * @return string
     */
    abstract public function handle($request);
}
