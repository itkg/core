<?php

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class CacheListenerTest extends \PHPUnit_Framework_TestCase
{

    public function testFetchEntityFromCache()
    {
        $stub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array('get', 'set'), array(array()));
        $stub->expects($this->once())->method('get')->will($this->returnValue(array('YEAH')));
        $dispatcherStub = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');
        $dispatcherStub->expects($this->once())->method('dispatch')->will($this->returnValue(array('cache.load', array('YEAH'))));
        $entity = new \Itkg\Core\Cache\CacheableData('my hash', 86400, array());

        $listener = new \Itkg\Core\Cache\Listener\CacheListener($stub, $dispatcherStub);
        $listener->fetchEntityFromCache(new \Itkg\Core\Event\EntityLoadEvent($entity));

        $this->assertEquals($entity->getDataForCache(), array('YEAH'));
        $this->assertTrue($entity->isLoaded());
    }

    public function testSetCacheEntity()
    {
        $stub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array('get', 'set'), array(array('YEAH')));
        $stub->expects($this->once())->method('set')->will($this->returnValue(array('YEAH')));
        $dispatcherStub = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');
        $dispatcherStub->expects($this->once())->method('dispatch')->will($this->returnValue(array('cache.set', array('YEAH'))));
        $entity = new \Itkg\Core\Cache\CacheableData('my hash', 86400, array());

        $listener = new \Itkg\Core\Cache\Listener\CacheListener($stub, $dispatcherStub);
        $listener->setCacheForEntity(new \Itkg\Core\Event\EntityLoadEvent($entity));

    }
} 