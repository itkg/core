<?php

namespace Itkg\Core\Command\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class Loader implements LoaderInterface
{
    /**
     * SQL queries
     *
     * @var array
     */
    protected $queries = array();

    /**
     * Doctrine connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;
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

    /**
     * Load script file
     *
     * @param $file
     * @return mixed
     */
    public function load($file)
    {
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