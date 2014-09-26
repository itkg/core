<?php

namespace Itkg\Core\Cache;
use Itkg\Core\CachableInterface;

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
     * @param \Itkg\Core\CachableInterface $item
     *
     * @return mixed
     */
    public function get(CachableInterface $item);

    /**
     * Set a value into the cache
     *
     * @param \Itkg\Core\CachableInterface $item
     *
     * @return void
     */
    public function set(CachableInterface $item);

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CachableInterface $item
     *
     * @return void
     */
    public function remove(CachableInterface $item);

    /**
     * Remove all cache
     *
     * @return void
     */
    public function removeAll();
}
