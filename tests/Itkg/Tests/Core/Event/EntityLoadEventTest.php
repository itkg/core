<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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