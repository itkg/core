<?php

namespace Itkg\Core\Model;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface ConfigInterface
{
    /**
     * Get config value
     *
     * @param $key
     */
    public function get($key);

    /**
     * Set config value
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function set($key, $value);

    /**
     * Key exists
     *
     * @param  string $key
     * @return mixed
     */
    public function has($key);
}
