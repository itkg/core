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

        $this->loadConfig();
        $this->loadRouting();
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
     * Load Config from config files
     */
    public function loadConfig()
    {
        $this->container['app']->getConfig()->load($this->getConfigFiles());

        $this->container['core']['dispatcher']->dispatch(KernelEvents::CONFIG_LOADED, new KernelEvent($this->container));
    }

    /**
     * Get an array of config Files
     *
     * @return array
     */
    protected function getConfigFiles()
    {
        return array(
            __DIR__ . '/Resources/Config/global.yml',
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
            __DIR__ . '/Resources/Config/routing.yml',
        );
    }

    /**
     * Load routing from routing files
     *
     * @return void
     */
    abstract public function loadRouting();

    /**
     * Create a response from a request
     *
     * @param $request
     * @return string
     */
    abstract public function handle($request);
}
