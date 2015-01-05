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

use Itkg\Core\Cache\AdapterAbstract;
use Itkg\Core\CacheableInterface;

class Memory extends AdapterAbstract
{
    /**
     * @var array
     */
    private $values = array();

    /**
     * Get value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return string
     */
    public function get(CacheableInterface $item)
    {
        return isset($this->values[$item->getHashKey()]) ? $this->values[$item->getHashKey()] : false;
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
        $this->values[$item->getHashKey()] = $item->getDataForCache();
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     * @return void
     */
    public function remove(CacheableInterface $item)
    {
        if (isset($this->values[$item->getHashKey()])) {
            unset($this->values[$item->getHashKey()]);
        }
    }

    /**
     * Remove cache
     *
     * @return void
     */
    public function removeAll()
    {
        $this->values = array();
    }
}
