<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class UseFirstWorkingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testFirstUnworkingAdapter()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));
        $stub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));
        $stub->expects($this->once())->method('get')->with($data);
        $stub->expects($this->once())->method('set')->with($data);

        $adapters = array(
            new \Itkg\Core\Cache\Adapter\Redis(array('host' => 'localhost', 'port' => 80)),
            $stub,
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters);
        $chain->get($data);
        $chain->set($data);
    }

    public function testFirstWorkingAdapter()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));

        $stub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));
        $stub->expects($this->once())->method('get')->with($data);
        $stub->expects($this->once())->method('set')->with($data);

        $secondStub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));
        $secondStub->expects($this->never())->method('get');
        $secondStub->expects($this->never())->method('set');

        $adapters = array(
            $stub,
            $secondStub
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters);
        $chain->get($data);
        $chain->set($data);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetUnWorkingAdapters()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));

        $adapters = array(
            new \Itkg\Core\Cache\Adapter\Redis(array('host' => 'localhost', 'port' => 80))
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters);
        $chain->get($data);
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testSetUnWorkingAdapters()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));

        $adapters = array(
            new \Itkg\Core\Cache\Adapter\Redis(array('host' => 'localhost', 'port' => 80))
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters);
        $chain->get($data);
    }

    /**
     * @expectedException \Exception
     */
    public function testRemoveUnWorkingAdapters()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));

        $adapters = array(
            new \Itkg\Core\Cache\Adapter\Redis(array('host' => 'localhost', 'port' => 80))
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters);
        $chain->remove($data);
    }

    /**
     * @expectedException \Exception
     */
    public function testRemoveAllUnWorkingAdapters()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));

        $adapters = array(
            new \Itkg\Core\Cache\Adapter\Redis(array('host' => 'localhost', 'port' => 80))
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters);
        $chain->remove($data);
    }
}
