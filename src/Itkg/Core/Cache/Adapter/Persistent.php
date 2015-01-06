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

use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\CacheableInterface;

/**
 * Class Persistent
 *
 * Store cache in specific adapter & keep in memory (with array storage)
 *
 * @package Itkg\Core\Cache\Adapter
 */
class Persistent extends Registry
{
    /**
     * @var \Itkg\Core\Cache\AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
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
        if (false !== $result = parent::get($item)) {
            return $result;
        }

        return $this->adapter->get($item);
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
        parent::set($item);
        $this->adapter->set($item);
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     * @return void
     */
    public function remove(CacheableInterface $item)
    {
        parent::remove($item);
        $this->adapter->remove($item);
    }

    /**
     * Remove cache
     *
     * @return void
     */
    public function removeAll()
    {
        parent::removeAll();
        $this->adapter->removeAll();
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param AdapterInterface $adapter
     * @return $this
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }
}
