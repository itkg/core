<?php

namespace Itkg\Core\Command\Script;

use Symfony\Component\Console\Output\Output;

class OutputQueryDisplay implements QueryDisplayInterface
{
    /**
     * @var QueryFormatterInterface
     */
    private $formatter;

    /**
     * @var Output
     */
    private $output;

    /**
     * Display a query
     *
     * @param string $query
     */
    public function display($query)
    {
        $this->output->write($this->formatter->format($query));
    }

    /**
     * @param QueryFormatterInterface $formatter
     * @return $this
     */
    public function setFormatter(QueryFormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * @param Output $output
     * @return $this
     */
    public function setOutput(Output $output)
    {
        $this->output = $output;

        return $this;
    }
} 