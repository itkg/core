<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Mock;

class RouteMatcher
{
    public function process()
    {
        return array(
            'params' => array(
                'controller'   => 'Itkg\Tests\Core\Mock\My',
                'action'       => 'index',
                'posts' => array(1)
            )
        );
    }
} 