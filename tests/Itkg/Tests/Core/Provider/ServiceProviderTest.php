<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    }
}
