<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Cache\Adapter\Chain;

use Itkg\Core\Cache\Adapter\Chain\Exception\UnhandledCacheException;
use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\CacheableInterface;

/**
 * Class UseAllStrategy
 *
 * This strategy use all adapters to save, retrieve and delete cache
 *
 * @package Itkg\Core\Cache\Adapter\Chain
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class UseAllStrategy implements CachingStrategyInterface
{
    /**
     * Get cache from any adapter if contains
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @throws UnhandledCacheException
     * @return mixed
     */
    public function get(array $adapters, CacheableInterface $item)
    {
        foreach ($adapters as $adapter) {

            if (false !== $value = $adapter->get($item)) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Set cache to all adapters
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @throws UnhandledCacheException
     * @return void
     */
    public function set(array $adapters, CacheableInterface $item)
    {
        foreach ($adapters as $adapter) {
            $adapter->set($item);
        }
    }

    /**
     * Remove cache from all adapters
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @return void
     */
    public function remove(array $adapters, CacheableInterface $item)
    {
        foreach ($adapters as $adapter) {
            $adapter->remove($item);
        }
    }

    /**
     * Remove all cache from all adapters
     *
     * @param array $adapters
     * @return void
     */
    public function removeAll(array $adapters)
    {
        foreach ($adapters as $adapter) {
            $adapter->removeAll();
        }
    }
}
