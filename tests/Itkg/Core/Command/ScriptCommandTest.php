<?php

namespace Itkg\Core\Command;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Command\Script\Loader;
use Itkg\Core\Command\Script\Migration\Factory;
use Itkg\Core\Command\Script\Runner;
use Itkg\Core\Command\Script\Setup;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ScriptCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testGetScripts()
    {
        $script = __DIR__.'/../../../mock/script/ticket.php';
        $command = $this->createCommand();

        $this->assertEquals(array($script), $command->getScripts(__DIR__.'/../../../mock/script'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWithNoScripts()
    {
        $command = $this->createCommand();

        $application = new Application();
        $application->add($command);

        if(!file_exists( __DIR__.'/../../../mock/void')) {
            mkdir( __DIR__.'/../../../mock/void');
            mkdir( __DIR__.'/../../../mock/void/script');
            mkdir( __DIR__.'/../../../mock/void/rollback');
        }

        $command = $application->find('itkg-core:script');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'source' => __DIR__.'/../../../mock/void'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testUncompleteScripts()
    {
        $command = $this->createCommand();

        $application = new Application();
        $application->add($command);

        if(!file_exists( __DIR__.'/../../../mock/void')) {
            mkdir( __DIR__.'/../../../mock/void');
            mkdir( __DIR__.'/../../../mock/void/script');
            mkdir( __DIR__.'/../../../mock/void/rollback');
        }

        $command = $application->find('itkg-core:script');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'source' => __DIR__.'/../../../mock/uncomplete'));
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

        return new ScriptCommand('itkg-core:script', new Setup($runner, $loader, $factory));
    }

} 