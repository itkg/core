<?php

namespace Itkg\Core\Command\Script;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Migration
{
    /**
     * @var array
     */
    private $queries;

    /**
     * @var array
     */
    private $rollbackQueries;

    /**
     * Constructor
     *
     * @param array $queries
     * @param array $rollbackQueries
     */
    public function __construct(array $queries = array(), array $rollbackQueries = array())
    {
        $this->queries         = $queries;
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
