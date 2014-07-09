<?php

namespace Itkg\Core\Command\Model;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Setup
{
    /**
     * Doctrine Connection
     *
     * @var Connection
     */
    protected $connection;

    /**
     * SQL queries
     *
     * @var array
     */
    protected $queries = array();

    /**
     * Doctrine connection
     *
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Doctrine connection
     *
     * @param Connection $connection
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Load script file
     * 
     * @param $file
     * @return mixed
     */
    public function loadScript($file)
    {
        return include $file;
    }

    /**
     * Add a query
     *
     * @param string $query
     */
    public function addQuery($query)
    {
        $this->queries[] = $query;
    }

    /**
     * Add a query from a query builder
     *
     * @param QueryBuilder $qb
     */
    public function addQueryFromBuilder(QueryBuilder $qb)
    {
        $this->queries[] = $qb->getSQL();
    }
}
