<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

/**
 * Class Parser
 *
 * Extract info from a query
 *
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

        $query      = trim(strtolower($query));
        $this->type = current(explode(' ', $query));
        $this->extractData();

        return $this;
    }

    /**
     * Extra some data from current query
     * Ex : table_name
     */
    protected function extractData()
    {
        if (preg_match(
            '/' . $this->type . ' .*(TABLE|INTO|FROM) *([a-zA-Z-_]*)/i',
            $this->query,
            $matches
        )
        ) {
            $this->data['table_name'] = $matches[2];
        }
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
        return $this->data;
    }
}
