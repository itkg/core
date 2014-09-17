<?php

namespace Itkg\Core\Cache\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class CacheEvent
 * @package Itkg\Cache\Event
 */
class CacheEvent extends Event
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $ttl = 0;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $size;

    /**
     * Constructor
     *
     * @param string $key
     * @param int $ttl
     * @param $value
     */
    public function __construct($key, $ttl = 0, $value = '')
    {
        $this->key = $key;
        $this->ttl = $ttl;
        $this->value = $value;
        $this->size = strlen($value);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}