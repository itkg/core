<?php

namespace Itkg\Core\Command;

use Itkg\Core\Command\DatabaseUpdate\FinderInterface;
use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Setup;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DatabaseUpdateCommand
 *
 * Symfony command to manage migrations (database update) script
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class DatabaseUpdateCommand extends Command
{
    /**
     * @var Setup
     */
    private $setup;

    /**
     * @var OutputQueryFactory
     */
    private $queryDisplayFactory;

    /**
     * Constructor
     *
     * @param string $name
     * @param Setup $setup
     * @param OutputQueryFactory $queryDisplayFactory
     */
    public function __construct($name = null, Setup $setup, OutputQueryFactory $queryDisplayFactory)
    {
        parent::__construct($name);

        $this->setup = $setup;
        $this->queryDisplayFactory = $queryDisplayFactory;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setDescription('Execute release SQL scripts & rollbacks')
            ->addArgument(
                'release',
                InputArgument::REQUIRED,
                'Please, provide a release where we can find script & rollback folder'
            )
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'Override default script path'
            )
            ->addOption(
                'script',
                null,
                InputOption::VALUE_REQUIRED,
                'Specify a script name (only this script & rollback will be executed)'
            )
            ->addOption(
                'force-rollback',
                null,
                InputOption::VALUE_NONE,
                'Force a rollback'
            )
            ->addOption(
                'rollback-first',
                null,
                InputOption::VALUE_NONE,
                'Execute a rollback before play script')
            ->addOption(
                'colors',
                null,
                InputOption::VALUE_NONE,
                'Colorize output'
            )
            ->addOption(
                'execute',
                null,
                InputOption::VALUE_NONE,
                'Execute the script'
            );
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @throws \LogicException
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setup->getLocator()->setParams(
            array(
                'release' => $input->getArgument('release'),
                'path' => $input->getOption('path'),
                'scriptName' => $input->getOption('script')
            )
        );

        $this->setup
            ->setForcedRollback($input->getOption('force-rollback'))
            ->setExecuteQueries($input->getOption('execute'))
            ->setRollbackedFirst($input->getOption('rollback-first'))
            ->run();

        $this->queryDisplayFactory
            ->create($input->getOption('colors') ? 'color' : '')
            ->setOutput($output)
            ->displayAll($this->setup->getQueries());
    }
}
