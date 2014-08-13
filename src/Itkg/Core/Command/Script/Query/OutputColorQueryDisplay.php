<?php

namespace Itkg\Core\Command\Script\Query;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class OutputColorQueryDisplay extends OutputQueryDisplay
{
    const SELECT_COLOR = 'green';
    const UPDATE_COLOR = 'blue';
    const DELETE_COLOR = 'red';
    const CREATE_COLOR = 'cyan';
    const ALTER_COLOR = 'yellow';

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
        $word = current(explode(' ', $query));

        switch ($word) {
            case 'ALTER':
                $color = self::ALTER_COLOR;
                break;
            case 'CREATE':
                $color = self::CREATE_COLOR;
                break;
            case 'DELETE':
                $color = self::DELETE_COLOR;
                break;
            case 'UPDATE':
                $color = self::UPDATE_COLOR;
                break;
            default:
                $color = self::SELECT_COLOR;
                break;
        }
        $style = new OutputFormatterStyle($color, null, array('bold'));

        $this->output->getFormatter()->setStyle($word, $style);

        return sprintf('<%s>%s</%s>', $word, $query, $word);
    }
}
