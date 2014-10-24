<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\Formatter;

use Monolog\Formatter\LineFormatter;

/**
 * Class CommandLineFormatter
 *
 * Specific formatter to format exception
 *
 * @package Itkg\Core\Command\Formatter
 */
class CommandLineFormatter extends LineFormatter
{
    /**
     * Format exception with normalizer
     * And replace msg record with formatted exception
     *
     * @param \Exception $exception
     * @param array $record
     * @return array|mixed|string
     */
    public function formatException(\Exception $exception, array $record = array())
    {
        $record['msg'] = $this->normalizeException($exception);

        return $this->format($record);
    }
}
