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
 * Class UseFirstWorkingStrategy
 *
 * This strategy is a FIFO strategy
 * First added adapter is used. If first adapter is down, second adapter will be used, etc.
 *
 * @package Itkg\Core\Cache\Adapter\Chain
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class UseFirstWorkingStrategy implements CachingStrategyInterface
{
    /**
     * Get cache from first up adapter
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @throws UnhandledCacheException
     * @return mixed
     */
    public function get(array $adapters, CacheableInterface $item)
    {
        foreach ($adapters as $adapter) {
            try {
                return $adapter->get($item);
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new UnhandledCacheException('No cache system is available');
    }

    /**
     * Set cache to first up adapter
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @throws UnhandledCacheException
     * @return void
     */
    public function set(array $adapters, CacheableInterface $item)
    {
        foreach ($adapters as $adapter) {
            try {
                $adapter->set($item);
                return;
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new UnhandledCacheException('No cache system is available');
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
