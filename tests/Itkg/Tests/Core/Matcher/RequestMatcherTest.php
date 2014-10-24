<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Matcher;


use Itkg\Core\Matcher\RequestMatcher;
use Itkg\Core\Route\Router;
use Symfony\Component\HttpFoundation\Request;

class RequestMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidSequence()
    {
        $router = new Router();
        $router->addRouteSequence('unknown');
        $matcher = new RequestMatcher($router);
        $matcher->matches(Request::createFromGlobals());
    }

    public function testRouteMatch()
    {
        $router = new Router();
        $router->addRouteSequence('Itkg\Tests\Core\Mock\RouteMatcher');
        $matcher = new RequestMatcher($router);
        $request = Request::createFromGlobals();
        $this->assertTrue($matcher->matches($request));

        $this->assertEquals('Itkg\Tests\Core\Mock\My', $request->attributes->get('controller'));
        $this->assertEquals('index', $request->attributes->get('action'));
        $params = $request->attributes->get('route_params');
        $this->assertArrayHasKey('posts', $params['params']);
    }
}
