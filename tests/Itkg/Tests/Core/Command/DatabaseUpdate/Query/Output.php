<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Query;

use Symfony\Component\Console\Output\Output as BaseOutput;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Output extends BaseOutput
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
