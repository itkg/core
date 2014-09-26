<?php

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAdapterDoesNotExist()
    {
        $factory = $this->createFactory();

        $factory->create('my_unknown_key', array());
    }

    /**
     * @expectedException Itkg\Core\Cache\InvalidConfigurationException
     */
    public function testConfigDoesNotExist()
    {
        $factory = $this->createFactory();

        $factory->create('redis', array());
    }

    public function testOK()
    {
        $factory = $this->createFactory();

        $redis = $factory->create('redis', array('redis' => array('host' => 'localhost')));
        $this->assertInstanceOf('Itkg\Core\Cache\Adapter\Redis', $redis);
    }

    public function createFactory()
    {
        return new \Itkg\Core\Cache\Factory(array(
            'redis' => 'Itkg\Core\Cache\Adapter\Redis'
        ));
    }
}
