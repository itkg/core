<?php

namespace Itkg\Core\Command\Script\Query;

use Symfony\Component\Console\Output\OutputInterface;

class OutputQueryDisplay
{
    /**
     * @var QueryFormatterInterface
     */
    protected $formatter;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Constructor
     *
     * @param QueryFormatterInterface $formatter
     */
    public function __construct(QueryFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

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
     * Display Batch of queries
     *
     * @param array $queries
     */
    public function displayAll(array $queries = array())
    {
        foreach ($queries as $query) {
            $this->display($query);
        }
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
     * Get output
     *
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }
} 