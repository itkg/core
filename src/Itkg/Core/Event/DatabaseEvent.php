<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class DatabaseEvent
 * @package Itkg\Debug\Event
 */
class DatabaseEvent extends Event
{
    /**
     * SQL query
     *
     * @var string
     */
    private $query;

    /**
     * Optionals data
     * @var array
     */
    private $data = array();

    /**
     * @var float
     */
    private $executionTime;

    /**
     * @param string $query
     * @param float $executionTime
     * @param array $data
     */
    public function __construct($query, $executionTime, $data)
    {
        $this->query = $query;
        $this->executionTime = $executionTime;
        $this->data = $data;
    }

    /**
     * @return float
     */
    public function getExecutionTime()
    {
        return $this->executionTime;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
