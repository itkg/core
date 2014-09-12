<?php

namespace Itkg\Tests\Core\Provider;

use Itkg\Core\ServiceContainer;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProviderTest 
{
    public function testRegister()
    {
        $container = new ServiceContainer();
        $container->register(new SerivceProvider($container));

        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcher', $container['core']['dispatcher']);
    }
}
