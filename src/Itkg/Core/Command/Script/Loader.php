<?php

namespace Itkg\Core\Command\Script;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Loader implements LoaderInterface
{
    /**
     * SQL queries
     *
     * @var array
     */
    private $queries = array();

    /**
     * Doctrine connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;
    /**
     * Constructor
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get query builder
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->connection->createQueryBuilder();
    }

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
     * Add a query from a query builder
     *
     * @param QueryBuilder $qb
     * @return $this
     */
    public function addQueryFromBuilder(QueryBuilder $qb)
    {
        $this->queries[] = $qb->getSQL();

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