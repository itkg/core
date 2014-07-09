<?php

namespace Itkg\Core\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ExecuteScriptCommand extends Command
{
    private $scripts = array();
    private $rollbacks = array();
    protected function configure()
    {
        $this
            ->setDescription('Execute release SQL scripts & rollbacks')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Please, provide a source folder where we can find script & rollback folder'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface  $output)
    {
        /**
         * @TODO : Use release & not source arguments
         */
        $source = $input->getArgument('source');

        $this->scripts = $this->getScripts($source.'/script');
        $this->rollbacks = $this->getScripts($source.'/rollback');

        if(sizeof(array_diff_key($this->scripts, $this->rollbacks)) != 0) {
            throw new \LogicException('Please provide as scripts files as rollbacks files with the same name');
        }

        $this->parse();
        $output->writeln($source);
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
            if($file->isDot()) continue;
            $files[$file->getFilename()] = $file->getPath().$file->getFilename();
        }
        sort($files);

        return $files;
    }

    public function parse()
    {
        foreach($this->scripts as $k => $script) {

        }
    }

}

