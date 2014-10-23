<?php

namespace Itkg\Tests\Core\Provider;

use Itkg\Core\Provider\ServiceProvider;
use Itkg\Core\ServiceContainer;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $container = new ServiceContainer();
        $container->register(new ServiceProvider($container));

        $this->assertInstanceOf('\Pimple', $container['core']);
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcher', $container['core']['dispatcher']);
        $this->assertInstanceOf('Itkg\Core\Response\Processor\CompressProcessor', $container['core']['response.processor.compress']);
    }
}
