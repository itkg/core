<?php

namespace Itkg\Core\Command\Script\Query;

use Symfony\Component\Console\Output\Output;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class TestOutput extends Output
{
    public $output = '';

    public function clear()
    {
        $this->output = '';
    }

    protected function doWrite($message, $newline)
    {
        $this->output .= $message.($newline ? "\n" : '');
    }
}
