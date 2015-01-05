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

use Itkg\Core\CacheableInterface;

/**
 * Interface CachingStrategyInterface
 *
 * @package Itkg\Core\Cache\Adapter\Chain
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface CachingStrategyInterface
{
    /**
     * Get cache data from adapters
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @return mixed
     */
    public function get(array $adapters, CacheableInterface $item);

    /**
     * Set cache data to adapters
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @return void
     */
    public function set(array $adapters, CacheableInterface $item);

    /**
     * Remove cache data from adapters
     *
     * @param array $adapters
     * @param CacheableInterface $item
     * @return void
     */
    public function remove(array $adapters, CacheableInterface $item);

    /**
     * Remove all cache from adapters
     *
     * @param array $adapters
     * @return void
     */
    public function removeAll(array $adapters);
} 