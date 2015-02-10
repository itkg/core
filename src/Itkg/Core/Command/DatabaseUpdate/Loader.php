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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class Loader
 *
 * Load queries script using this context class
 *
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
        $this->queries[] = new Query(preg_replace('/^(\s)+/m', ' ', $query));

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
        /**
         * @TODO : How to manage query params ?
         */
        $this->queries[] = new Query($qb->getSQL());

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
