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

use Itkg\Core\Cache\Adapter\Chain\CachingStrategyInterface;
use Itkg\Core\Cache\Adapter\Chain\SimpleStrategy;
use Itkg\Core\Cache\AdapterAbstract;
use Itkg\Core\CacheableInterface;

class Chain extends AdapterAbstract
{
    /**
     * @var array
     */
    protected $adapters;

    /**
     * @var CachingStrategyInterface
     */
    protected $cachingStrategy;

    /**
     * @param array $adapters
     * @param CachingStrategyInterface $cachingStrategy
     */
    public function __construct(array $adapters, CachingStrategyInterface $cachingStrategy = null)
    {
        $this->adapters = $adapters;

        if (null === $cachingStrategy) {
            $cachingStrategy = new SimpleStrategy();
        }

        $this->cachingStrategy = $cachingStrategy;
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
        return $this->cachingStrategy->get(
            $this->adapters,
            $item
        );
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
        $this->cachingStrategy->set(
            $this->adapters,
            $item
        );
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     * @return void
     */
    public function remove(CacheableInterface $item)
    {
        $this->cachingStrategy->remove(
            $this->adapters,
            $item
        );
    }

    /**
     * Remove cache
     *
     * @return void
     */
    public function removeAll()
    {
        $this->cachingStrategy->removeAll($this->adapters);
    }

    /**
     * @return array
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * @return CachingStrategyInterface|SimpleStrategy
     */
    public function getCachingStrategy()
    {
        return $this->cachingStrategy;
    }

    /**
     * @param array $adapters
     * @return $this
     */
    public function setAdapters(array $adapters)
    {
        $this->adapters = $adapters;

        return $this;
    }

    /**
     * @param CachingStrategyInterface $cachingStrategy
     * @return $this
     */
    public function setCachingStrategy(CachingStrategyInterface $cachingStrategy)
    {
        $this->cachingStrategy = $cachingStrategy;

        return $this;
    }
}
