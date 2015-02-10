<?php

namespace MyCanal\Cache\Adapter;


use Itkg\Core\Cache\Adapter\RedisCluster;
use Itkg\Core\Cache\CacheableData;

class RedisClusterTest extends \PHPUnit_Framework_TestCase
{

    public function testAdapter()
    {
        $cacheableData = new CacheableData('hash_key', 86400, 'value');

        $masterConnection = $this->getMock('\Redis');
        $slaveConnection   = $this->getMock('\Redis');

        $masterConnection->expects($this->once())->method('set')->with('hash_key', 'value');
        $masterConnection->expects($this->once())->method('expire')->with('hash_key', 86400);
        $masterConnection->expects($this->once())->method('delete')->with('hash_key');

        $slaveConnection->expects($this->once())->method('get')->with('hash_key')->will($this->returnValue('value'));

        $adapter = $this->getMock('Itkg\Core\Cache\Adapter\RedisCluster', array('getConnection', 'getSlaveConnection'), array(array()));

        $adapter->expects($this->any())->method('getConnection')->will($this->returnValue($masterConnection));
        $adapter->expects($this->any())->method('getSlaveConnection')->will($this->returnValue($slaveConnection));

        $adapter->set($cacheableData);
        $this->assertEquals('value', $adapter->get($cacheableData));
        $adapter->remove($cacheableData);

    }

    public function testRemoveAll()
    {
        $masterConnection = $this->getMock('\Redis');
        $masterConnection->expects($this->once())->method('flushAll');

        $adapter = $this->getMock('Itkg\Core\Cache\Adapter\RedisCluster', array('getConnection', 'getSlaveConnection'), array(array()));
        $adapter->expects($this->any())->method('getConnection')->will($this->returnValue($masterConnection));

        $adapter->removeAll();

    }

    /**
     * @expectedException \RedisException
     */
    public function testConnectMaster()
    {
        $adapter = new RedisCluster(array(
            'master' => array(
                'HOST' => '192.168.0.1',
                'PORT' => 6971
            )
        ));
        $adapter->removeAll();
    }

    /**
     * @expectedException \RedisException
     */
    public function testConnectSlave()
    {
        $adapter = new RedisCluster(array(
            'slave' => array(
                'HOST' => '192.168.0.1',
                'PORT' => 6971
            )
        ));
        $adapter->get(new CacheableData('hash_key', 86400, 'value'));
    }

    public function testSetWithNoTtl()
    {
        $adapter = new RedisCluster(array());
        $stub = $this->getMock('\Redis');
        $cacheable = new \Itkg\Core\Cache\CacheableData('hash_key', null, 'my saved value');
        $stub->expects($this->never())->method('expire');
        $adapter->setConnection($stub);
        $adapter->set($cacheable);
    }
}