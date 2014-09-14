<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;
use Itkg\Core\Command\DatabaseUpdate\Query;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface DecoratorInterface
{
    /**
     * Decorate a query
     *
     * @param \Itkg\Core\Command\DatabaseUpdate\Query $query
     * @return array decorated queries
     */
    public function decorate(Query $query);

    /**
     * Decorate queries
     *
     * @param array $queries
     * @return array Decorated queries
     */
    public function decorateAll(array $queries = array());
} 