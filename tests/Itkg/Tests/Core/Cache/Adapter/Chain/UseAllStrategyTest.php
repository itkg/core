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
class UseAllStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetRemoveAndRemoveAll()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));
        $stub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));
        $stub->expects($this->once())->method('get')->with($data)->will($this->returnValue('yeah!'));
        $stub->expects($this->once())->method('set')->with($data);
        $stub->expects($this->once())->method('remove')->with($data);
        $stub->expects($this->once())->method('removeAll');
        $secondStub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));
        $secondStub->expects($this->never())->method('get');
        $secondStub->expects($this->once())->method('set');
        $secondStub->expects($this->once())->method('remove')->with($data);
        $secondStub->expects($this->once())->method('removeAll');

        $adapters = array(
            $stub,
            $secondStub
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters, new \Itkg\Core\Cache\Adapter\Chain\UseAllStrategy());
        $chain->get($data);
        $chain->set($data);
        $chain->remove($data);
        $chain->removeAll();
    }

    public function testFirstAdapterNoContainsCache()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));
        $stub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));
        $stub->expects($this->once())->method('get')->with($data)->will($this->returnValue(false));
        $secondStub = $this->getMock('Itkg\Core\Cache\Adapter\Redis', array(), array(array()));
        $secondStub->expects($this->once())->method('get');

        $adapters = array(
            $stub,
            $secondStub
        );

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters, new \Itkg\Core\Cache\Adapter\Chain\UseAllStrategy());
        $chain->get($data);
    }
}
