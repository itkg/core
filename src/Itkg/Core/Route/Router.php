<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Route;

/**
 * Class Router
 * @package Itkg\Core\Route
 */
class Router
{
    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var array
     */
    protected $routeSequences = array();

    /**
     * Constructor
     *
     * @param array $routes
     */
    public function __construct(array $routes = array())
    {
        $this->routes = $routes;
    }

    /**
     * @param Route $route
     * @param $name
     */
    public function addRoute(Route $route, $name)
    {
        $this->routes[$name] = $route;
    }

    /**
     * Set routes
     *
     * @param array $routes
     */
    public function setRoutes(array $routes = array())
    {
        $this->routes = $routes;
    }

    /**
     * Get routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param string $sequence
     */
    public function addRouteSequence($sequence)
    {
        $this->routeSequences[] = $sequence;
    }

    /**
     * Get routes sequences
     *
     * @return array
     */
    public function getRouteSequences()
    {
        return $this->routeSequences;
    }
}
