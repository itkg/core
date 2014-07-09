<?php

namespace Itkg\Core\Command\Model;

class Migration
{
    /**
     * @var array
     */
    protected $queries;

    /**
     * @var array
     */
    protected $rollbackQueries;

    /**
     * Constructor
     *
     * @param array $queries
     * @param array $rollbackQueries
     */
    public function __construct(array $queries = array(), array $rollbackQueries = array())
    {
        $this->queries = $queries;
        $this->rollbackQueries = $rollbackQueries;
    }

    /**
     * Get queries
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Get rollback queries
     *
     * @return array
     */
    public function getRollbackQueries()
    {
        return $this->rollbackQueries;
    }
} 