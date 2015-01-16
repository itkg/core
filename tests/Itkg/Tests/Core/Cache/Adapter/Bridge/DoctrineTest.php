<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Itkg\Core\Cache\Adapter\Bridge\Doctrine;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class DoctrineTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetRemoveAndRemoveAll()
    {
        $cacheable = new \Itkg\Core\Cache\CacheableData('hash_key', 86400, 'my saved value');

        $stub = $this->getMock('Doctrine\Common\Cache\ArrayCache');
        $stub->expects($this->once())->method('fetch')->with('hash_key');
        $stub->expects($this->once())->method('save')->with('hash_key', 'my saved value', 86400);
        $stub->expects($this->once())->method('delete')->with('hash_key');
        $stub->expects($this->once())->method('deleteAll');

        $adapter = new Doctrine($stub);
        $adapter->get($cacheable);
        $adapter->set($cacheable);
        $adapter->remove($cacheable);
        $adapter->removeAll();
    }
}
