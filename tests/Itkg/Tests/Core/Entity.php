<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core;

use Itkg\Core\CacheableInterface;
use Itkg\Core\EntityAbstract;
use Itkg\Tests\Core\SubEntity;
/**
 * Fake entity model for testing purpose
 */
class Entity extends EntityAbstract implements \ArrayAccess, CacheableInterface
{
    /**
     * Property prefix used in arrayAccess & database columns
     */
    const PROPERTY_PREFIX = 'ENTITY';

    /**
     * Entity without getter
     * @var int
     */
    protected $subEntityWithoutGetter;

    /**
     * @var \Itkg\Tests\Core\Entity
     */
    protected $subEntity;

    public function __construct()
    {
        $this->subEntity = new SubEntity;
        $this->subEntityWithoutGetter = new SubEntity;
        return $this;
    }

    public function getSub()
    {
        return $this->subEntity;
    }

    public function setDummy($dummy)
    {
        $this->dummy = $dummy;
        return $this;
    }

    /**
     * Offset to retrieve
     * Return existence of key
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $key = $this->getPropertyName($key);
        return isset($this->$key);
    }

    /**
     * Whether a offset exists
     *
     * @param  mixed    $offset
     * @return boolean  True on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getPropertyValue($offset);
    }

    /**
     * Offset to set
     *
     * @param  mixed           $offset
     * @param  mixed           $value
     * @return EntityAbstract
     */
    public function offsetSet($offset, $value)
    {
        return $this->setDataFromArray(array($offset => $value));
    }

    /**
     * Offset unset
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->has($offset)) {
            unset($this->$offset);
        }
    }

    /**
     * Hash key getter
     *
     * @return string
     */
    public function getHashKey()
    {
        return '_entity';
    }
}
