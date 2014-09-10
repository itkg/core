<?php

namespace Itkg\Core\Command\DatabaseUpdate\Template;


use Itkg\Core\Command\DatabaseUpdate\LoaderInterface;

class Loader implements LoaderInterface
{
    private $queries = array();

    /**
     * Add a query
     *
     * @param string $query
     * @return $this
     */
    public function addQuery($query)
    {
        $this->queries[] = $query;

        return $this;
    }


    /**
     * Load script file
     *
     * @param $file
     * @return $this
     */
    public function load($file)
    {
        $this->queries = array();

        include $file;

        return $this;
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