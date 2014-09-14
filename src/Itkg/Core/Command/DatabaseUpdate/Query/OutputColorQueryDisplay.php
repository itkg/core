<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Class OutputColorQueryDisplay
 *
 * Provide some colors for queries
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class OutputColorQueryDisplay extends OutputQueryDisplay
{
    /**
     * Queries colors
     *
     * @var array
     */
    private $colors = array(
        'select' => 'green',
        'update' => 'blue',
        'delete' => 'red',
        'create_table' => 'cyan',
        'create_index' => 'cyan',
        'create_sequence' => 'cyan',
        'create_synonym' => 'cyan',
        'drop_table' => 'cyan',
        'drop_index' => 'cyan',
        'drop_sequence' => 'cyan',
        'drop_synonym' => 'cyan',
        'alter' => 'yellow',
        'grant' => 'yellow',
        'insert' => 'white'
    );

    /**
     * Display a query (with colors)
     *
     * @param Query $query
     */
    public function display(Query $query)
    {
        parent::display($this->changeStyle($query));
    }

    /**
     * Change query color by creating a new Style
     *
     * @param Query $query
     * @return string
     */
    private function changeStyle(Query $query)
    {
        $style = new OutputFormatterStyle($this->colors[$query->getType()], null, array('bold'));

        $this->output->getFormatter()->setStyle($query->getType(), $style);

        return sprintf('<%s>%s</%s>', $query->getType(), $query, $query->getType());
    }
}
