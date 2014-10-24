<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    /**d
     * Decorate queries
     *
     * @param array $queries
     * @return array Decorated queries
     */
    public function decorateAll(array $queries = array());
}
