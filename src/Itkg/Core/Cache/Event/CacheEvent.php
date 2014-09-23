<?php

namespace Itkg\Core\Cache\Event;

use Itkg\Core\CachableInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CacheEvent
 * @package Itkg\Cache\Event
 */
class CacheEvent extends Event
{
    /**
     * @var CachableInterface
     */
    private $cachableData;

    /**
     * Constructor
     *
     * @param string $key
     * @param int $ttl
     * @param $value
     */
    public function __construct(CachableInterface $cachableData)
    {
        $this->cachableData = $cachableData;
    }

    /**
     * @return CachableInterface
     */
    public function getCachabledata()
    {
        return $this->cachableData;
    }
}
