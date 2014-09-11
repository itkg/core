<?php

namespace Itkg\Tests\Core\Command\Provider;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Application;
use Itkg\Core\Config;
use Itkg\Core\ServiceContainer;
use Itkg\Core\Command\Provider\ServiceCommandProvider;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceCommandProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceContainer
     */
    private $container;
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->container = new Servicecontainer();
        $this->container
            ->setApp(new Application())
            ->setConfig(new Config());
        $this->container->register(new ServiceCommandProvider());
    }

    public function testRunner()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\RunnerInterface', $this->container['itkg-core.command.db_update.runner']);
    }

    /**
     * @expectedException \Exception
     */
    public function testDoctrineDependency()
    {
        $loader = $this->container['itkg-core.command.database_update.loader'];
    }

    public function testSetup()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Setup', $this->container['itkg-core.command.db_update.setup']);
    }

    public function testDecorator()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Query\Decorator', $this->container['itkg-core.command.db_update.decorator']);
    }

    public function testCommand()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdateCommand', $this->container['itkg-core.command.database_update']);
    }

    private function loadDoctrine()
    {
        $this->container['doctrine.connection'] = $this->container->share(function() {
            $params = array(
                'dbname' => 'DBNAME',
                'user'   => 'USER',
                'password' => 'PWD',
                'host' => '',
                'driver' => 'oci8'
            );

            $config = new Configuration();

            return DriverManager::getConnection($params, $config);
        });
    }
}
