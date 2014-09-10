<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

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
        'SELECT' => 'green',
        'UPDATE' => 'blue',
        'DELETE' => 'red',
        'CREATE' => 'cyan',
        'ALTER'  => 'yellow'
    );

    /**
     * Display a query (with colors)
     *
     * @param string $query
     */
    public function display($query)
    {
        parent::display($this->changeStyle($query));
    }

    /**
     * Change query color by creating a new Style
     *
     * @param $query
     * @return string
     */
    private function changeStyle($query)
    {
        $query = trim(strtoupper($query));
        $word  = current(explode(' ', $query));

        $style = new OutputFormatterStyle($this->colors[$word], null, array('bold'));

        $this->output->getFormatter()->setStyle($word, $style);

        return sprintf('<%s>%s</%s>', $word, $query, $word);
    }
}
