<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

/**
 * Class Parser
 * @package Itkg\Core\Command\DatabaseUpdate\Query
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Parser
{
    /**
     * @var
     */
    private $type;

    /**
     * @var
     */
    private $data = array();

    /**
     * @var string
     */
    private $query;

    /**
     * @param $query
     */
    public function parse($query)
    {
        $this->query = $query;

        $query = trim(strtolower($query));
        $this->type = current(explode(' ', $query));

        /**
         * @todo : extra data
         */
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData()
    {
        // table name, fields ?

        return $this->data;
    }
}
