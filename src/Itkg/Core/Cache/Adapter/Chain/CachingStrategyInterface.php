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

interface CachingStrategyInterface
{
    /**
     * @param array $adapters
     * @param CacheableInterface $item
     * @return mixed
     */
    public function get(array $adapters, CacheableInterface $item);

    /**
     * @param array $adapters
     * @param CacheableInterface $item
     * @return void
     */
    public function set(array $adapters, CacheableInterface $item);

    /**
     * @param array $adapters
     * @param CacheableInterface $item
     * @return void
     */
    public function remove(array $adapters, CacheableInterface $item);

    /**
     * @param array $adapters
     * @return void
     */
    public function removeAll(array $adapters);
} 