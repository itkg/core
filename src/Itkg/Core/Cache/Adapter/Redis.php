<?php

namespace Itkg\Core\Cache\Adapter;

use Itkg\Core\Cache\AdapterAbstract;
use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\CacheableInterface;
use Itkg\Core\ConfigInterface;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Redis extends AdapterAbstract implements AdapterInterface
{
    /**
     * Connection instance
     * @var \Redis
     */
    protected $connection;

    /**
     * Connection getter
     *
     * @return \Redis
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new \Redis();

            if (!$this->connection->pconnect(
                $this->config['default']['host'],
                $this->config['default']['port']
            )) {
                throw new \RedisException('Unable to connect');
            }
        }

        return $this->connection;
    }

    /**
     * @param \Redis $connection
     * @return $this
     */
    public function setConnection(\Redis $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Get value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return string
     */
    public function get(CacheableInterface $item)
    {
        return $this->getConnection()->get($item->getHashKey());
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
        $this->getConnection()->set($item->getHashKey(), $item->getDataForCache());
        if (null !== $item->getTtl()) {
            $this->getConnection()->expire($item->getHashKey(), $item->getTtl());
        }
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     * @return void
     */
    public function remove(CacheableInterface $item)
    {
        $this->getConnection()->delete($item->getHashKey());
    }

    /**
     * Remove cache
     *
     * @return void
     */
    public function removeAll()
    {
        $this->getConnection()->flushAll();
    }
}
