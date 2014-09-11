<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OutputQueryDisplay
 *
 * Query display class (using formatter)
 *
 * @package Itkg\Core\Command\DatabaseUpdate\Query
 */
class OutputQueryDisplay
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Constructor
     *
     * @param FormatterInterface $formatter
     */
    public function __construct(FormatterInterface $formatter)
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
     * @param FormatterInterface $formatter
     * @return $this
     */
    public function setFormatter(FormatterInterface $formatter)
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
