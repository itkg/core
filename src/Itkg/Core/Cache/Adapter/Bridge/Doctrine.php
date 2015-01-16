<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Cache\Adapter\Bridge;

use Itkg\Core\Cache\AdapterAbstract;
use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\CacheableInterface;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Class Doctrine
 *
 * This adapter handle doctrine cache providers
 *
 * @package Itkg\Core\Cache\Adapter\Bridge
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Doctrine extends AdapterAbstract implements AdapterInterface
{
    /**
     * @var CacheProvider
     */
    protected $provider;

    /**
     * @param CacheProvider $provider
     */
    public function __construct(CacheProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return mixed
     */
    public function get(CacheableInterface $item)
    {
        return $this->provider->fetch($item->getHashKey());
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
        $this->provider->save($item->getHashKey(), $item->getDataForCache(), $item->getTtl());
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return void
     */
    public function remove(CacheableInterface $item)
    {
        $this->provider->delete($item->getHashKey());
    }

    /**
     * Remove all cache
     *
     * @return void
     */
    public function removeAll()
    {
        $this->provider->deleteAll();
    }
}
