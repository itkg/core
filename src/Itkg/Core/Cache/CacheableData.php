<?php

namespace Itkg\Core\Cache;

use Itkg\Core\CacheableInterface;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class CacheableData implements CacheableInterface
{
    /**
     * @var string
     */
    private $hashKey;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var string
     */
    private $data;

    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @param $hashKey
     * @param $ttl
     * @param $data
     */
    public function __construct($hashKey, $ttl, $data)
    {
        $this->hashKey = $hashKey;
        $this->ttl = $ttl;
        $this->setDataFromCache($data);
    }

    /**
     * Hash key getter
     *
     * @return string
     */
    public function getHashKey()
    {
        return $this->hashKey;
    }


    /**
     * Get cache TTL
     *
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Return if object is already loaded from cache
     *
     * @return bool
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * Set is loaded
     *
     * @param bool $isLoaded
     */
    public function setIsLoaded($isLoaded = true)
    {
        $this->loaded = $isLoaded;
    }

    /**
     * Get data from entity for cache set
     *
     * @return mixed
     */
    public function getDataForCache()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setDataFromCache($data)
    {
        $this->data = $data;
    }
}
