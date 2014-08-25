<?php

namespace Itkg\Core\Command;

use Itkg\Core\Command\Formatter\CommandLineFormatter;
use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CommandExecuterAbstract
 * @package Itkg\Core\Command
 */
abstract class CommandExecuterAbstract extends Command
{
    /**
     * @var Formatter\CommandLineFormatter
     */
    protected $formatter;

    /**
     * @var array
     */
    protected $defautlRecord = array();

    /**
     * @param $name
     * @param CommandLineFormatter $formatter
     * @param array $defautlRecord
     */
    public function __construct($name, CommandLineFormatter $formatter, array $defautlRecord = array())
    {
        parent::__construct($name);
        $this->formatter = $formatter;
        $this->defautlRecord = $defautlRecord;
    }

    /**
     * Execute command and type
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @throws \LogicException
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->doExecute($input, $output);
        } catch(\Exception $e) {
            $this->writeException($output, $e);
        }
    }

    /**
     * Executed command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @throws \LogicException
     * @return int|null|void
     */
    abstract public function doExecute(InputInterface $input, OutputInterface $output);

    /**
     * Write a message with extra record params
     *
     * @param OuputInterface $output
     * @param $message
     * @param array $record
     */
    public function write(OuputInterface $output, $message, array $record = array())
    {
        $record = array_merge($this->defautlRecord, $record);
        $record['msg'] = $message;
        $output->writeln($this->formatter->format($record));
    }

    /**
     * Set a default record
     *
     * @param array $defaultRecord
     */
    public function setDefaultRecord(array $defaultRecord = array())
    {
        $this->defautlRecord = $defaultRecord;
    }

    /**
     * Write an exception with extra record params
     *
     * @param OuputInterface $output
     * @param \Exception $exception
     * @param array $record
     */
    public function writeException(OuputInterface $output, \Exception $exception, array $record = array())
    {
        $record = array_merge($this->defautlRecord, $record);
        $output->writeln($this->formatter->formatException($exception, $record));
    }
}
