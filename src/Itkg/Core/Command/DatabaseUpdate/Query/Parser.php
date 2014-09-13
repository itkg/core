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
     * @param $query
     * @return $this
     */
    public function parse($query)
    {
        $this->data = array();
        $this->type = '';
        $this->extractData($query);

        return $this;
    }

    /**
     * Extra some data from current query
     * Ex : table_name
     */
    protected function extractData($query)
    {
        $query = preg_replace('/OR *REPLACE/', '', $query);
        /**
         * @TODO : Grant parse
         */
        if (preg_match(
            '/(CREATE|UPDATE|DELETE|ALTER|INSERT|DROP|SELECT|GRANT) *(SEQUENCE|INDEX|SYNONYM|TABLE|INTO|FROM|) *([a-zA-Z-_]*) */i',
            $query,
            $matches
        )
        ) {
            $this->type = trim(strtolower($matches[1]));
            if($this->type == 'create') {
                $this->type = strtolower(trim(sprintf('%s_%s', $matches[1], $matches[2])));
            }

            $this->data['identifier'] = $matches[3];
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
