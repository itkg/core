<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate;

/**
 * Class Migration
 *
 * Represent couple of queries & rollback queries
 *
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
