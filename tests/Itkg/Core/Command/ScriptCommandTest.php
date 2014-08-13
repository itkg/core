<?php

namespace Itkg\Core\Command;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Command\Script\Finder;
use Itkg\Core\Command\Script\Loader;
use Itkg\Core\Command\Script\Locator;
use Itkg\Core\Command\Script\Migration\Factory;
use Itkg\Core\Command\Script\Query\OutputQueryFactory;
use Itkg\Core\Command\Script\Query\QueryFormatter;
use Itkg\Core\Command\Script\Runner;
use Itkg\Core\Command\Script\Setup;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ScriptCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testWithNoScripts()
    {
        $command = $this->createCommand();

        $application = new Application();
        $application->add($command);

        if (!file_exists( __DIR__.'/../../../mock/void')) {
            mkdir( __DIR__.'/../../../mock/void');
            mkdir( __DIR__.'/../../../mock/void/script');
            mkdir( __DIR__.'/../../../mock/void/rollback');
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

        if (!file_exists( __DIR__.'/../../../mock/uncomplete/rollback')) {
            mkdir( __DIR__.'/../../../mock/uncomplete/rollback');
        }


        $command = $application->find('itkg-core:script');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'path' => __DIR__.'/../../../mock', 'release' => 'uncomplete'));
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
        $queryFactory = new OutputQueryFactory(new QueryFormatter());

        return new ScriptCommand('itkg-core:script', new Setup($runner, $loader, $factory, $locator), $queryFactory);
    }

} 