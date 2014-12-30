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
class ChainTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetRemoveAndRemoveAll()
    {
        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));
        $adapters = array(new \Itkg\Core\Cache\Adapter\Memory(array()));

        $strategy = $this->getMock('Itkg\Core\Cache\Adapter\Chain\SimpleStrategy');

        $strategy->expects($this->once())->method('get')->with($adapters, $data);
        $strategy->expects($this->once())->method('set')->with($adapters, $data);
        $strategy->expects($this->once())->method('remove')->with($adapters, $data);
        $strategy->expects($this->once())->method('get')->with($adapters);

        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters, $strategy);
        $chain->set($data);
        $chain->get($data);
        $chain->remove($data);
        $chain->removeAll();
    }

    public function testDefaultStrategy()
    {
        $adapters = array(new \Itkg\Core\Cache\Adapter\Memory(array()));
        $chain = new \Itkg\Core\Cache\Adapter\Chain($adapters);

        $this->assertInstanceOf('Itkg\Core\Cache\Adapter\Chain\SimpleStrategy', $chain->getCachingStrategy());
    }
}
