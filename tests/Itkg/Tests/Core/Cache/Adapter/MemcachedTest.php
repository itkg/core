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

        // Bug in memcached declaration
        // See https://github.com/sebastianbergmann/phpunit-mock-objects/issues/192
        ini_set('error_reporting', E_ALL & ~E_STRICT);

        $connectionMock = $this->getMockBuilder('\Memcached')
            ->setMethods(array('set', 'get', 'delete', 'flush', 'setOptions'))
            ->getMock();
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

        ini_set('error_reporting', E_ALL);
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

