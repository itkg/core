<?php

namespace Itkg\Core\Command;

use Itkg\Core\Command\Script\Query\OutputQueryFactory;
use Itkg\Core\Command\Script\Setup;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ScriptCommand extends Command
{
    /**
     * @var array
     */
    private $scripts = array();

    /**
     * @var array
     */
    private $rollbacks = array();

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
                'source',
                InputArgument::REQUIRED,
                'Please, provide a source folder where we can find script & rollback folder'
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
        /**
         * @TODO : Use release & not source arguments
         */
        $source = $input->getArgument('source');

        $this->scripts   = $this->getScripts(sprintf('%s/script', $source));
        $this->rollbacks = $this->getScripts(sprintf('%s/rollback', $source));

        if (empty($this->scripts)) {
            throw new \RuntimeException(sprintf('No scripts were found in %s directory', $source));
        }

        if (sizeof(array_diff_key($this->scripts, $this->rollbacks)) != 0) {
            throw new \LogicException('Please provide as scripts files as rollbacks files with the same name');
        }

        $this->setup
            ->setForcedRollback($input->getOption('force-rollback'))
            ->setExecuteQueries($input->getOption('execute'))
            ->setRollbackedFirst($input->getOption('rollback-first'));

        foreach ($this->scripts as $k => $script) {
            $this->setup->createMigration($script, $this->rollbacks[$k]);
        }

        $this->setup->run();

        $this->queryDisplayFactory
            ->create($input->getOption('colors') ? 'color' : '')
            ->setOutput($output)
            ->displayAll($this->setup->getQueries());
    }

    /**
     * Get scripts from a specific folder
     *
     * @param $folder
     * @return array
     */
    public function getScripts($folder)
    {
        $files = array();
        foreach (new \DirectoryIterator($folder) as $file) {
            if ($file->isDot()) {
                continue;
            }
            $files[$file->getFilename()] = sprintf('%s/%s', $file->getPath(), $file->getFilename());
        }
        sort($files);

        return $files;
    }
}
