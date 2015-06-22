<?php

namespace Itkg\Core\Cache\Adapter;

use Itkg\Core\Cache\AdapterAbstract;
use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\CacheableInterface;

/**
 * Memcached cache adapter
 */
class Memcached extends AdapterAbstract implements AdapterInterface
{
    /**
     * Memcached connection instance
     * @var \Memcached
     */
    protected $connection;

    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = array(
        \Memcached::OPT_COMPRESSION          => true,
        \Memcached::OPT_LIBKETAMA_COMPATIBLE => true,
        \Memcached::OPT_CONNECT_TIMEOUT      => 500,
        \Memcached::OPT_SERVER_FAILURE_LIMIT => 3,
    );

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->setConnection(new \Memcached);
    }

    /**
     * Set connection
     *
     * @param \Memcached $memcached
     *
     * @return $this
     *
     */
    public function setConnection(\Memcached $memcached)
    {
        $this->connection = $memcached;

        if (!count($this->connection->getServerList())) {
            $this->configureServers();
        }

        $this->setOptions();
        return $this;
    }

    /**
     * Configure servers
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function configureServers()
    {
        if (!array_key_exists('server', $this->config)) {
            throw new \InvalidArgumentException('Configuration key "server" must be set');
        }

        $servers = $this->config['server'];

        // Single server
        if (array_key_exists('host', $servers)) {
            $servers = array($servers);
        }

        $this->connection->addServers($servers);
    }

    /**
     * Configure options
     *
     * @return void
     */
    protected function setOptions()
    {
        $options = $this->defaultOptions;

        if (array_key_exists('options', $this->config)) {
            $options = array_merge($options, $this->config['options']);
        }

        $this->connection->setOptions($options);
    }

    /**
     * Get value from cache
     * Must return false when cache is expired or invalid
     *
     * @param CacheableInterface $item
     *
     * @return mixed
     */
    public function get(CacheableInterface $item)
    {
        return $this->connection->get($item->getHashKey());
    }

    /**
     * Set a value into the cache
     *
     * @param CacheableInterface $item
     *
     * @return void
     */
    public function set(CacheableInterface $item)
    {
        $this->connection->set($item->getHashKey(), $item->getDataForCache(), $item->getTtl());
    }

    /**
     * Remove a value from cache
     *
     * @param CacheableInterface $item
     *
     * @return void
     */
    public function remove(CacheableInterface $item)
    {
        $this->connection->delete($item->getHashKey());
    }

    /**
     * Remove all cache
     *
     * @return void
     */
    public function removeAll()
    {
        $this->connection->flush();
    }
}
