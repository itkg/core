<?php
/**
 * Created by PhpStorm.
 * User: pdenis
 * Date: 20/10/14
 * Time: 22:13
 */

namespace Itkg\Tests\Core\Resolver;


use Itkg\Core\Resolver\ControllerResolver;
use Itkg\Core\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ControllerResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveController()
    {
        $request = Request::create('/');
        $request->attributes->replace(array(
            'action'     => 'index'
        ));
        $container = new ServiceContainer();

        $resolver = new ControllerResolver($container);
        $callable = $resolver->resolve($request, 'Itkg\Tests\Core\Mock\My');

        $this->assertInstanceOf('Itkg\Tests\Core\Mock\MyController', $callable[0]);
        $this->assertEquals('indexAction', $callable[1]);

        $this->assertEquals($request, $callable[0]->getRequest());
        $this->assertEquals($container, $callable[0]->getContainer());
    }

    public function testResolveControllerLegacy()
    {
        $request = Request::create('/');
        $request->attributes->replace(array(
            'action'     => 'index',
            'controller'  => 'Itkg/Tests/Core/Mock/Legacy',
            'route_params' => array(
                'directory' => '/Tests/Core/Mock'
            )
        ));
        $container = new ServiceContainer();

        $resolver = new ControllerResolver($container);
        $resolver->setPath(__DIR__.'/../../../..');
        $callable = $resolver->getController($request);

        $this->assertInstanceOf('Itkg_Tests_Core_Mock_Legacy_Controller', $callable[0]);
        $this->assertEquals('call', $callable[1]);

        $this->assertEquals($request, $callable[0]->getRequest());
        $this->assertEquals($container, $callable[0]->getContainer());
        $this->assertEquals($resolver->getArguments($request, $callable), array('indexAction'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetArgumentsIfActionDoesNotExist()
    {
        $request = Request::create('/');
        $request->attributes->replace(array(
            'action'     => 'unknown',
            'controller'  => 'Itkg/Tests/Core/Mock/Legacy',
            'route_params' => array(
                'directory' => '/Tests/Core/Mock'
            )
        ));
        $container = new ServiceContainer();

        $resolver = new ControllerResolver($container);
        $resolver->setPath(__DIR__.'/../../../..');
        $callable = $resolver->getController($request);

        $resolver->getArguments($request, $callable);

    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetControllerNotFound()
    {
        $request = Request::create('/');
        $request->attributes->replace(array(
            'action'     => 'index'
        ));
        $container = new ServiceContainer();

        $resolver = new ControllerResolver($container);

        $resolver->getController($request);
    }
}
