<?php

class MemcachedTest extends \PHPUnit_Framework_TestCase
{
    public function testAdapterBehaviour()
    {
        $config = array (
            'server' => array('host' => '127.0.0.1', 'port' => 123, 'weight' => 1),
            'options' => array(),
        );

        $dataMock = $this->getMock('\Itkg\Core\CacheableInterface');
        $dataMock->expects($this->exactly(3))
            ->method('getHashKey')
            ->will($this->returnValue('HASHKEY'));

        $connectionMock = $this->getMock('\Memcached');
        $connectionMock->expects($this->once())
            ->method('set')
            ->with('HASHKEY');
        $connectionMock->expects($this->once())
            ->method('get')
            ->with('HASHKEY');
        $connectionMock->expects($this->once())
            ->method('delete')
            ->with('HASHKEY');
        $connectionMock->expects($this->once())
            ->method('flush');
        $connectionMock->expects($this->once())
            ->method('setOptions');

        $memcached = new \Itkg\Core\Cache\Adapter\Memcached($config);
        $memcached->setConnection($connectionMock);
        $memcached->set($dataMock);
        $memcached->get($dataMock);
        $memcached->remove($dataMock);
        $memcached->removeAll();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNoServerConfigException()
    {
        $memcached = new \Itkg\Core\Cache\Adapter\Memcached(array());
        $memcached->configureServers();
    }
}

