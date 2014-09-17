<?php

namespace Itkg\Core\Command\DatabaseUpdate;


use Itkg\Core\Command\DatabaseUpdate\Layout\Parser;
use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Display
 *
 * Display queries using formatter, colors & template
 *
 * @package Itkg\Core\Command\DatabaseUpdate
 */
class Display
{
    /**
     * @var Layout\Parser
     */
    private $templateParser;
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var Query\Formatter
     */
    private $queryFormatter;

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
     * @param Parser $templateParser
     * @param Formatter $queryFormatter
     */
    public function __construct(Parser $templateParser, Formatter $queryFormatter)
    {
        $this->templateParser = $templateParser;
        $this->queryFormatter = $queryFormatter;
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

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Display queries (using Output)
     *
     * @param array $queries
     * @param bool $useColor
     * @param bool $useTemplate
     * @throws \RuntimeException
     */
    public function displayQueries(array $queries = array(), $useColor = false, $useTemplate = false)
    {
        if (!$this->output) {
            throw new \RuntimeException('No output is defined');
        }
        $this->formatQueries($queries, $useColor);

        if ($useTemplate) {
            $content = $this->templateParser->parse($queries)->getContent();
        } else {

            $content = implode('', $queries);
        }

        $this->output->write($content);
    }

    /**
     * @return Parser
     */
    public function getTemplateParser()
    {
        return $this->templateParser;
    }

    /**
     *
     * @param array $queries
     * @param bool $useColor
     */
    protected function formatQueries(array $queries = array(), $useColor = false)
    {
        foreach ($queries as $key => $query) {
            $queries[$key] = $this->formatQuery($query, $useColor);
        }
    }

    /**
     * @param Query $query
     * @param bool $useColor
     * @return Query
     */
    protected function formatQuery(Query $query, $useColor = false)
    {
        $value = $this->queryFormatter->format((string)$query);

        if ($useColor) {
            $value = sprintf('<%s>%s</%s>', $query->getType(), $value, $query->getType());
            $this->createStyle($query->getType());
        }

        // Set query value without parse again
        $query->setValue($value, false);

        return $query;
    }

    /**
     * @param $queryType
     */
    protected function createStyle($queryType)
    {
        if (!$this->output->getFormatter()->hasStyle($queryType)) {
            $style = new OutputFormatterStyle($this->colors[$queryType], null, array('bold'));
            $this->output->getFormatter()->setStyle($queryType, $style);
        }
    }
}
