<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Legacy;

use Itkg\Core\ApplicationInterface;
use Itkg\Core\Event\KernelEvent;
use Itkg\Core\Event\RequestEvent;
use Itkg\Core\Event\ResponseEvent;
use Itkg\Core\KernelAbstract;
use Itkg\Core\Resolver\ControllerResolver;
use Itkg\Core\Route\Route;
use Itkg\Core\ServiceContainer;
use Itkg\Core\YamlLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Class Kernel
 * @package Itkg\Core\Legacy
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Kernel extends KernelAbstract
{
    /**
     * @param ServiceContainer $container
     * @param ApplicationInterface $app
     * @param \Itkg\Core\Resolver\ControllerResolver $resolver
     */
    public function __construct(ServiceContainer $container, ApplicationInterface $app, ControllerResolver $resolver)
    {
        \Pelican::$config = $app->getConfig();
        parent::__construct($container, $app, $resolver);

        $this->overridePelicanLoader();

        \Pelican_Cache::$eventDispatcher = $container['core']['dispatcher'];
        \Pelican_Db::$eventDispatcher = $container['core']['dispatcher'];
        \Pelican_Request::$eventDispatcher = $container['core']['dispatcher'];
        \Backoffice_Div_Helper::$kernel = $this;
        $this->resolver->setPath($this->container['config']['APPLICATION_CONTROLLERS']);

    }

    /**
     * Load routing from routing files
     *
     * @throws \RuntimeException
     * @return $this
     */
    protected function loadRouting()
    {
        $parser = new YamlParser();
        $routes = array();
        foreach ($this->getRoutingFiles() as $file) {
            $routes = array_merge($routes, $parser->parse(file_get_contents($file)));
        }

        foreach ($routes as $name => $routeInfos) {
            $this->processRouteInfos($name, $routeInfos);
        }

        return $this;
    }

    /**
     * @param $name
     * @param $routeInfos
     */
    private function processRouteInfos($name, $routeInfos)
    {
        $className = null;
        if (isset($routeInfos['sequence'])) {
            $className = $routeInfos['sequence'];
            $this->container['core']['router']->addRouteSequence($className);
        }

        if (isset($routeInfos['pattern'])) {
            if (!isset($routeInfos['arguments'])) {
                $routeInfos['arguments'] = array();
            }

            $route = new Route($routeInfos['pattern'], $routeInfos['arguments'], $className);

            if (isset($routeInfos['defaults'])) {
                $route->defaults($routeInfos['defaults']);
            }
            if (isset($routeInfos['params'])) {
                $route->pushRequestParams($routeInfos['params']);
            }
            $this->container['core']['router']->addRoute($route, $name);
        }
    }

    protected function overridePelicanLoader()
    {

    }
}
