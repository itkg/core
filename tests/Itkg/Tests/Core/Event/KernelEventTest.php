<?php

namespace Itkg\Tests\Core\Event;

use Itkg\Core\Event\KernelEvent;
use Itkg\Core\ServiceContainer;

class KernelEventTest extends \PHPUnit_Framework_TestCase
{
    public function testContainer()
    {
        $container = new ServiceContainer();

        $event = new KernelEvent($container);

        $this->assertEquals($container, $event->getContainer());
    }
} 