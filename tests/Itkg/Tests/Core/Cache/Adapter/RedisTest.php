<?php

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class RedisTest extends \PHPUnit_Framework_TestCase
{

    public function testAdapter()
    {
        $adapter = $this->createAdapter();



    }


    private function createAdapter()
    {
        $mock = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));

        $mock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue(new \M6Web\Component\RedisMock\RedisMock()));

        return $mock;
    }
} 