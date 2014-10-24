<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface ConfigInterface
{
    /**
     * Get all config values
     *
     * @return array
     */
    public function all();

    /**
     * Get config value
     *
     * @param $key
     */
    public function get($key);

    /**
     * Key exists
     *
     * @param  string $key
     * @return mixed
     */
    public function has($key);

    /**
     * Set config value
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     */
    public function set($key, $value);
}
