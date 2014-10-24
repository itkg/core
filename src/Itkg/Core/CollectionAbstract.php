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
 * Class CollectionAbstract
 * @package Itkg\Core
 */
class CollectionAbstract implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    protected $elements;

    /**
     * Add entity to collection
     *
     * @param CollectionableInterface $element
     *
     * @return $this
     */
    public function add(CollectionableInterface $element)
    {
        $this->elements[$element->getId()] = $element;
        return $this;
    }

    /**
     * Get an entity by its ID
     *
     * @param $id
     *
     * @return EntityAbstract
     *
     * @throws \InvalidArgumentException
     */
    public function getById($id)
    {
        if (isset($this->elements[$id])) {
            return $this->elements[$id];
        }

        throw new \InvalidArgumentException(sprintf('Element for id %s does not exist', $id));
    }

    /**
     * Get All entities IDs
     *
     * @return array
     */
    public function getIds()
    {
        return array_keys($this->elements);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->elements);
    }
}
