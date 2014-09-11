<?php

namespace Itkg\Core\Command;

use Doctrine\DBAL\Connection;
use Itkg\Core\Command\DatabaseUpdate\Query\DecoratorInterface;
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
     * @var Decorator
     */
    private $decorator;

    /**
     * Constructor
     *
     * @param string $name
     * @param Setup $setup
     * @param OutputQueryFactory $queryDisplayFactory
     */
    public function __construct(Setup $setup, OutputQueryFactory $queryDisplayFactory, DecoratorInterface $decorator, $name = null)
    {
        parent::__construct($name);

        $this->setup = $setup;
        $this->queryDisplayFactory = $queryDisplayFactory;
        $this->decorator = $decorator;
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
                'Execute a rollback before play script'
            )
            ->addOption(
                'with-template',
                null,
                InputOption::VALUE_NONE,
                'Decorate queries with custom templates'
            )
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

        $queries = $this->setup($input);

        if ($input->getOption('with-template')) {
            $queries = $this->decorator->decorateAll($queries);
        }

        $this->display($input, $output, $queries);
    }

    /**
     * Start migration setup
     *
     * @return array
     */
    protected function setup(InputInterface $input)
    {
        return $this->setup
            ->setForcedRollback($input->getOption('force-rollback'))
            ->setExecuteQueries($input->getOption('execute'))
            ->setRollbackedFirst($input->getOption('rollback-first'))
            ->run();
    }

    /**
     * Display queries
     */
    protected function display(InputInterface $input, OutputInterface $output, array $queries = array())
    {
        $this->queryDisplayFactory
            ->create($input->getOption('colors') ? 'color' : '')
            ->setOutput($output)
            ->displayAll($queries);
    }
}
