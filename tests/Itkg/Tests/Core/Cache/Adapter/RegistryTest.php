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
        $memory = new \Itkg\Core\Cache\Adapter\Registry(array());

        $data = new \Itkg\Core\Cache\CacheableData('my_key', null, array('values'));
        $otherData = new \Itkg\Core\Cache\CacheableData('my_key2', null, array('values2'));

        $memory->set($data);
        $this->assertEquals($data->getDataForCache(), $memory->get($data));

        $memory->remove($data);
        $this->assertFalse($memory->get($data));

        $memory->set($otherData);
        $memory->set($data);

        $memory->removeAll();
        $this->assertFalse($memory->get($data));
        $this->assertFalse($memory->get($otherData));
    }
}

