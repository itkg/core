<?php

namespace Itkg\Core\Cache;
use Itkg\Core\CacheableInterface;

/**
 * Cache adapter interface
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface AdapterInterface
{
    /**
     * Get value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return mixed
     */
    public function get(CacheableInterface $item);

    /**
     * Set a value into the cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return void
     */
    public function set(CacheableInterface $item);

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return void
     */
    public function remove(CacheableInterface $item);

    /**
     * Remove all cache
     *
     * @return void
     */
    public function removeAll();
}
