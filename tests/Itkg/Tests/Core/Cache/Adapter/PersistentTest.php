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
class MemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetRemoveAndRemoveAll()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));

        $adapter = $this->getMock('Itkg\Core\Cache\Adapter\Memory', array(), array(array()));
        $adapter->expects($this->exactly(2))->method('get')->with($data)->will($this->returnValue(false));
        $adapter->expects($this->once())->method('set')->with($data);
        $adapter->expects($this->once())->method('remove')->with($data);
        $adapter->expects($this->once())->method('removeAll');

        $persistent = new \Itkg\Core\Cache\Adapter\Persistent($adapter);

        $persistent->get($data);
        $persistent->set($data);
        $persistent->get($data);
        $persistent->remove($data);
        $this->assertFalse($persistent->get($data));
        $persistent->removeAll();
    }
}
