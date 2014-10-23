<?php

namespace Itkg\Core;

use Itkg\Core\Event\KernelEvent;
use Itkg\Core\Event\KernelEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class KernelAbstract extends HttpKernel
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    /**
     * @param ServiceContainer $container
     * @param ApplicationInterface $app
     * @param ControllerResolverInterface $resolver
     */
    public function __construct(ServiceContainer $container, ApplicationInterface $app, ControllerResolverInterface $resolver)
    {
        $this->container = $container;
        $this->resolver = $resolver;

        $this->container->setApp($app);
        $this
            ->loadConfig()
            ->loadRouting();

        $this->container->setConfig($app->getConfig());
        $this->dispatchEvent(KernelEvents::APP_LOADED, new KernelEvent($container));
        $this->container['kernel'] = $this;

        parent::__construct($this->container['core']['dispatcher'], $resolver);


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
     * @return $this
     */
    protected function loadConfig()
    {
        $this->container['app']->getConfig()->load($this->getConfigFiles());

        $this->dispatchEvent(KernelEvents::CONFIG_LOADED, new KernelEvent($this->container));
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
     * Dispatch an event with the current core dispatcher
     *
     * @param $name
     * @param Event $event
     */
    protected function dispatchEvent($name, Event $event)
    {
        return $this->container['core']['dispatcher']->dispatch($name, $event);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        if ($type == HttpKernelInterface::SUB_REQUEST) {
            $request->query->add(
                array_merge(
                    $_POST,
                    $_GET,
                    $request->query->all()
                )
            );
        } else {
            $this->container['request'] = $request;
        }

        return parent::handle($request, $type, $catch);
    }

    /**
     * Load routing from routing files
     *
     * @return $this
     */
    abstract protected function loadRouting();
}
