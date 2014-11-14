<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command;

use Itkg\Core\Command\DatabaseList\FinderInterface;
use Itkg\Core\Command\DatabaseUpdate\LocatorInterface;
use Itkg\Core\Command\DatabaseUpdate\ReleaseChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DatabaseListCommand
 *
 * Symfony command to list migrations release (database update) script
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class DatabaseListCommand extends Command
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var FinderInterface
     */
    private $finder;

    /**
     * @var DatabaseUpdate\ReleaseChecker
     */
    private $checker;

    /**
     * @param LocatorInterface $locator
     * @param DatabaseList\FinderInterface $finder
     * @param DatabaseUpdate\ReleaseChecker $checker
     * @param null $name
     */
    public function __construct(
        LocatorInterface $locator,
        FinderInterface $finder,
        ReleaseChecker $checker,
        $name = null
    ) {
        parent::__construct($name);

        $this->locator = $locator;
        $this->checker = $checker;
        $this->finder  = $finder;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setDescription('List releases SQL scripts & rollbacks')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'Override default script path'
            );

    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configureOptions($input);

        $rows = $failed = array();
        foreach ($this->finder->findAll() as $release) {
            $this->locator->setParams(array('release' => $release));
            $scripts = $rollbacks = array();
            $status  = '<fg=green>OK</fg=green>';

            try {
                $scripts   = $this->locator->findScripts();
                $rollbacks = $this->locator->findRollbackScripts();
                $this->checker->check($scripts, $rollbacks);
            } catch (\Exception $e) {
                $status   = sprintf('<fg=red>%s</fg=red>', $e->getMessage());
                $failed[] = $release;
            }

            $rows[] = array($release, count($scripts), count($rollbacks), $status);
        }

        $this->display($output, $rows, $failed);
    }

    /**
     * Configure input options
     *
     * @param InputInterface $input
     */
    protected function configureOptions(InputInterface $input)
    {
        if ($input->getOption('path')) {
            $this->finder->setPath($input->getOption('path'));
            $this->locator->setParams(array('path' => $input->getOption('path')));
        }
    }

    /**
     * Display result as a table
     *
     * @param OutputInterface $output
     * @param array $rows
     */
    protected function display(OutputInterface $output, array $rows = array(), array $failed = array())
    {
        $this->getApplication()->getHelperSet()->get('table')
            ->setHeaders(array('Name', 'Scripts', 'Rollbacks', 'Status'))
            ->setRows($rows)
            ->render($output);

        if (!empty($failed)) {
            $output->writeln(sprintf('<fg=red>Check failed for release(s) %s</fg=red>', implode(', ', $failed)));
        }
    }
}
