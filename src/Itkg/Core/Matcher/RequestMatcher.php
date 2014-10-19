<?php

namespace Itkg\Core\Matcher;

use Itkg\Core\Route\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * Class RequestMatcher
 * @package Itkg\Core\Matcher
 */
class RequestMatcher implements RequestMatcherInterface
{
    protected $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return null|array
     * @throws \RuntimeException
     */
    public function matches(Request $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');

        foreach ($this->router->getRouteSequences() as $sequence) {
            if (!class_exists($sequence)) {
                throw new \RuntimeException(sprintf('Route sequence %s does not exist', $sequence));
            }

            $routeMatcher = new $sequence(
                $pathInfo,
                $this->router->getRoutes()
            );

            $params = $routeMatcher->process();
            if (is_array($params) && !empty($params)) {
                return $this->processParams($request, $params);
            }
        }

        return null;
    }

    /**
     * @param Request $request
     * @param array $params
     * @return array
     */
    private function processParams(Request $request, array $params = array())
    {
        if (isset($params['params']['controller'])) {
            $request->attributes->set('controller', $params['params']['controller']);
        }

        if (isset($params['params']['action'])) {
            $request->attributes->set('action', $params['params']['action']);
        }

        $request->attributes->set('route_params', $params);

        return $params;
    }
}
