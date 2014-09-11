<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface DecoratorInterface
{
    /**
     * Decorate a query
     *
     * @return decorated queries
     */
    public function decorate($query);

    /**
     * Decorate queries
     *
     * @return array Decorated queries
     */
    public function decorateAll(array $queries = array());
} 