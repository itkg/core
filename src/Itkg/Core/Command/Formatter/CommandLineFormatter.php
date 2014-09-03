<?php

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