<?php

namespace Itkg\Core\Command\DatabaseUpdate\Template;

use Itkg\Core\Command\DatabaseUpdate\LoaderInterface;
use Itkg\Core\Command\DatabaseUpdate\Query;

class Loader implements LoaderInterface
{
    /**
     * @var array
     */
    private $queries = array();

    /**
     * @var array
     */
    private $data = array();

    /**
     * Add a query
     *
     * @param string $query
     * @return $this
     */
    public function addQuery($query)
    {
        $this->queries[] = new Query( $this->override($query));

        return $this;
    }


    /**
     * Load script file
     *
     * @param $file
     * @return $this
     */
    public function load($file, array $data = array())
    {
        $this->queries = array();
        $this->data = $data;

        include $file;

        return $this;
    }

    /**
     * Replace some parameters with value
     * parameter looks like {key}
     * @param $query
     * @return string
     */
    public function override($query)
    {
        foreach ($this->data as $key => $value) {
            $query = str_replace(
                sprintf('{%s}', $key),
                $value,
                $query
            );
        }

        return $query;
    }

    /**
     * Get SQL queries
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }
}
