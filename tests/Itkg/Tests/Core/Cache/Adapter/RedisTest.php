<?php

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class RedisTest extends \PHPUnit_Framework_TestCase
{

    public function testAdapter()
    {
        $cacheable = new \Itkg\Core\Cache\CachableData('hash_key', 86400, 'my saved value');
        $stub = $this->getMock('\Redis');
        $stub->expects($this->once())->method('delete')->with('hash_key');
        $stub->expects($this->once())->method('flushAll');
        $stub->expects($this->once())->method('set')->with('hash_key', 'my saved value');
        $stub->expects($this->once())->method('expire')->with('hash_key', 86400);
        $stub->expects($this->once())->method('get')->with('hash_key');

        $adapter = new \Itkg\Core\Cache\Adapter\Redis(array());
        $adapter->setConnection($stub);

        $adapter->remove($cacheable);
        $adapter->removeAll();
        $adapter->set($cacheable);
        $adapter->get($cacheable);


    }

    public function testSetWithNoTtl()
    {
        $adapter = new \Itkg\Core\Cache\Adapter\Redis(array());
        $stub = $this->getMock('\Redis');
        $cacheable = new \Itkg\Core\Cache\CachableData('hash_key', null, 'my saved value');
        $stub->expects($this->never())->method('expire');
        $adapter->setConnection($stub);
        $adapter->set($cacheable);
    }
}
