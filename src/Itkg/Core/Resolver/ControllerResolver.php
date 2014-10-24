<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Resolver;

use Itkg\Core\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ControllerResolver
 * @package Itkg\Core\Resolver
 */
class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @var string
     */
    private $path;

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
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param Request $request
     * @param $controllerName
     * @param array $routeParams
     * @return array
     */
    public function resolve(Request $request, $controllerName, $routeParams = array())
    {
        if (class_exists($controllerName . 'Controller')) {
            $controllerName = $controllerName . 'Controller';
            $controller = new $controllerName;
            $callable = array($controller, $request->attributes->get('action') . 'Action');
        } else {
            $controller = $this->createLegacyController($request, $controllerName, $routeParams);
            $callable = array($controller, 'call');
        }

        $controller
            ->setContainer($this->container)
            ->setRequest($request);

        return $callable;
    }

    /**
     * @param Request $request
     * @param $controllerName
     * @param $routeParams
     * @return mixed
     */
    private function createLegacyController(Request $request, $controllerName, $routeParams)
    {
        $path = $this->getLegacyControllerPath($controllerName, $routeParams);
        include_once $path;

        $controllerName = $this->getLegacyControllerName($controllerName, $routeParams);

        $controller = new $controllerName($request);
        $controller
            ->setPath($path);

        return $controller;
    }

    /**
     * @param $controllerName
     * @param $routeParams
     * @return string
     */
    private function getLegacyControllerPath($controllerName, $routeParams)
    {
        $directory = (isset($routeParams['params']['directory'])) ? $routeParams['params']['directory'] : '';

        return sprintf(
            '%s/%s/%s.php',
            $this->path,
            trim(($directory ? '/' . $directory : ''), '/'),
            $controllerName
        );
    }

    /**
     * @param $controllerName
     * @param $routeParams
     * @return string
     */
    private function getLegacyControllerName($controllerName, $routeParams)
    {
        $directory = (isset($routeParams['params']['directory'])) ? $routeParams['params']['directory'] . '_' : '';

        $controllerName = str_replace(' ', '/', ucwords(str_replace('/', ' ', str_replace('/', '_', $controllerName))));
        $path = '';


        /**
         * cas particulier
         */
        if ($directory == 'Pelican/Controller') {
            $return = $path . $directory . $controllerName;
        } else {
            $return = $path . $directory . $controllerName . '_Controller';
        }

        return str_replace('/', '_', $return);
    }

    /**
     * Returns the Controller instance associated with a Request.
     *
     * As several resolvers can exist for a single application, a resolver must
     * return false when it is not able to determine the controller.
     *
     * The resolver must only throw an exception when it should be able to load
     * controller but cannot because of some errors made by the developer.
     *
     * @param Request $request A Request instance
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return callable|false A PHP callable representing the Controller,
     *                        or false if this resolver is not able to determine the controller
     *
     * @api
     */
    public function getController(Request $request)
    {
        if (!$request->attributes->has('controller')) {
            throw new NotFoundHttpException(sprintf('No controller found for pathinfo %s', $request->getPathInfo()));
        }

        $controllerName = $request->attributes->get('controller');

        return $this->resolve($request, $controllerName, $request->attributes->get('route_params'));
    }

    /**
     * Returns the arguments to pass to the controller.
     *
     * @param Request $request A Request instance
     * @param callable $controller A PHP callable
     *
     * @return array An array of arguments to pass to the controller
     *
     * @throws \RuntimeException When value for argument given is not provided
     *
     * @api
     */
    public function getArguments(Request $request, $controller)
    {
        $actionName = sprintf('%sAction', $request->attributes->get('action') ? $request->attributes->get('action') : 'index');

        if (!method_exists($controller[0], $actionName)) {
            throw new \RuntimeException(
                sprintf('Method %s does not exist for controller %s', $actionName, get_class($controller[0]))
            );
        }

        return array(
            $actionName
        );

    }
}
