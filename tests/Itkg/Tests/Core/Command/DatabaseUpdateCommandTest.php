<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Command;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Command\DatabaseUpdate\Display;
use Itkg\Core\Command\DatabaseUpdate\Layout\Parser;
use Itkg\Core\Command\DatabaseUpdate\Loader;
use Itkg\Core\Command\DatabaseUpdate\Locator;
use Itkg\Core\Command\DatabaseUpdate\Migration\Factory;
use Itkg\Core\Command\DatabaseUpdate\Query\Decorator;
use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;
use Itkg\Core\Command\DatabaseUpdate\ReleaseChecker;
use Itkg\Core\Command\DatabaseUpdate\Runner;
use Itkg\Core\Command\DatabaseUpdate\Setup;
use Itkg\Core\Command\DatabaseUpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DatabaseUpdateCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testWithNoScripts()
    {
        $command = $this->createCommand();

        $application = new Application();
        $application->add($command);

        if (!file_exists(TEST_BASE_DIR.'/data/void')) {
            mkdir(TEST_BASE_DIR.'/data/void');
            mkdir(TEST_BASE_DIR.'/data/void/script');
            mkdir(TEST_BASE_DIR.'/data/void/rollback');
        }

        $command = $application->find('itkg-core:script');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'release' => 'undefined'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testUncompleteScripts()
    {
        $command = $this->createCommand();

        $application = new Application();
        $application->add($command);

        if (!file_exists(TEST_BASE_DIR.'/data/uncomplete/rollback')) {
            mkdir(TEST_BASE_DIR.'/data/uncomplete/rollback');
        }

        $command = $application->find('itkg-core:script');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                '--path'    => TEST_BASE_DIR.'/data',
                'release' => 'uncomplete',
                'with-template' => true
            )
        );
    }

    public function testDisplay()
    {
        $command = $this->createCommand();

        $application = new Application();
        $application->add($command);

        $command = $application->find('itkg-core:script');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                '--path'    => TEST_BASE_DIR,
                'release' => 'data'
            )
        );

        $result = <<<EOF
CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT, TEST_NAME varchar(255));
CREATE TABLE MYC_TEST_SCRIPT2 (TEST_SCRIPT_ID INT, TEST_NAME varchar(255));

EOF;

        $this->assertEquals($result, $commandTester->getDisplay());
    }

    private function createCommand()
    {
        $params = array(
            'dbname' => 'DBNAME',
            'user'   => 'USER',
            'password' => 'PWD',
            'host' => '',
            'driver' => 'oci8'
        );

        $config = new Configuration();
        $connection = DriverManager::getConnection($params, $config);

        $loader = new Loader($connection);
        $runner = new Runner($connection);
        $factory = new Factory();
        $locator = new Locator();
        $decorator = new Decorator(new \Itkg\Core\Command\DatabaseUpdate\Template\Loader());
        $display = new Display(new Parser(), new Formatter());

        return new DatabaseUpdateCommand(new Setup($runner, $loader, $factory, $locator, $decorator, new ReleaseChecker()), $display, 'itkg-core:script');
    }

}