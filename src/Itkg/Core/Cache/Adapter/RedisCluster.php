<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Cache\Adapter;

use Itkg\Core\CacheableInterface;

/**
 * Class Redis
 *
 * Redis cluster adapter
 * - Read from slave
 * - Write into master
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class RedisCluster extends Redis
{
    /**
     * Slave connection
     *
     * @var \Redis
     */
    private $slaveConnection;

    /**
     * Get value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return mixed
     */
    public function get(CacheableInterface $item)
    {
        return $this->getSlaveConnection()->get($item->getHashKey());
    }

    /**
     * Set a value into the cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return void
     */
    public function set(CacheableInterface $item)
    {
        $con = $this->getConnection();
        $con->set($item->getHashKey(), $item->getDataForCache());
        if (null != $item->getTtl()) {
            $con->expire($item->getHashKey(), $item->getTtl());
        }
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return void
     */
    public function remove(CacheableInterface $item)
    {
        $this->getConnection()->delete($item->getHashKey());
    }

    /**
     * Remove cache from decache databases
     *
     * @return void
     */
    public function removeAll()
    {
        $this->getConnection()->flushAll();
    }

    /**
     * Get slave connection
     * If connection is not initialized, we create a new one
     *
     * @return \Redis
     */
    public function getSlaveConnection()
    {
        if (null === $this->slaveConnection) {
            $this->slaveConnection = new \Redis();
            $this->slaveConnection->pconnect(
                $this->config['slave']['HOST'],
                $this->config['slave']['PORT']
            );
        }
        return $this->slaveConnection;
    }
    /**
     * Connection getter
     *
     * @return \Redis
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new \Redis();
            $this->connection->pconnect(
                $this->config['master']['HOST'],
                $this->config['master']['PORT']
            );
        }
        return $this->connection;
    }

}
