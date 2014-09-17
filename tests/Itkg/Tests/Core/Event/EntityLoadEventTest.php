<?php

namespace Itkg\Tests\Core\Event;


use Itkg\Core\Event\EntityLoadEvent;
use Itkg\Tests\Core\Entity;

class EntityLoadEventTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $entity = new Entity();
        $event = new EntityLoadEvent($entity);
        $this->assertEquals($entity, $event->getEntity());
    }
} 