<?php

namespace Itkg\Core\Command;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Command\Script\Loader;
use Itkg\Core\Command\Script\Migration\Factory;
use Itkg\Core\Command\Script\Runner;
use Itkg\Core\Command\Script\Setup;

class ScriptCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testGetScripts()
    {
        $script = __DIR__.'/../../../mock/script/ticket.php';
        $command = $this->createCommand();

        $this->assertEquals(array($script), $command->getScripts(__DIR__.'/../../../mock/script'));
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

        return new ScriptCommand('name', new Setup($runner, $loader, $factory));
    }
} 