<?php

namespace Itkg\Core\Command\DatabaseUpdate\Layout;

class Loader
{
    const PATH = '/script/templates/layout.php';

    /**
     * Available vars
     *
     * @var array
     */
    private $vars = array(
        'alter',
        'grant',
        'create_index',
        'create_synonym',
        'create_table',
        'update',
        'delete',
        'select',
        'drop_index',
        'drop_table',
        'drop_sequence',
        'drop_synonym',
    );

    public function load(array $queries = array())
    {
        if(!file_exists(self::PATH)) {
            return;
        }


    }
}