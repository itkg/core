<?php

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