<?php

namespace Itkg\Core\Cache\Adapter;


use Itkg\Core\Cache\AdapterAbstract;
use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\CachableInterface;
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

            $this->connection->pconnect(
                $this->config['default']['host'],
                $this->config['default']['port']
            );
        }

        return $this->connection;
    }

    /**
     * Get value from cache
     *
     * @param \Itkg\Core\CachableInterface $item
     *
     * @return string
     */
    public function get(CachableInterface $item)
    {
        return $this->connection->get($item->getHashKey());
    }

    /**
     * Set a value into the cache
     *
     * @param \Itkg\Core\CachableInterface $item
     *
     * @return void
     */
    public function set(CachableInterface $item)
    {
        $this->connection->set($item->getHashKey(), $item->getDataForCache());
        if (null !== $item->getTtl()) {
            $this->connection->expire($item->getHashKey(), $item->getTtl());
        }
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CachableInterface $item
     * @return void
     */
    public function remove(CachableInterface $item)
    {
        $this->connection->eval(
            "return redis.call('DEL', unpack(redis.call('KEYS', ARGV[1] .. '*')))",
            array($item->getHashKey())
        );
    }

    /**
     * Remove cache
     *
     * @return void
     */
    public function removeAll()
    {
        $this->connection->flushAll();
    }
}
