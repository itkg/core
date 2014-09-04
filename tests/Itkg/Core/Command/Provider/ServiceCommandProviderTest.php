<?php

namespace Itkg\Core\Command\Provider;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Application;
use Itkg\Core\Config;
use Itkg\Core\ServiceContainer;

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

    public function testLoader()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\LoaderInterface', $this->container['itkg-core.command.database_update.loader']);
    }

    public function testRunner()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\RunnerInterface', $this->container['itkg-core.command.database_update.runner']);
    }

    /**
     * @expectedException \Exception
     */
    public function testDoctrineDependency()
    {
        $loader = $this->container['itkg-core.command.database_update.loader'];
    }

    public function testOutputQueryFactory()
    {
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory', $this->container['itkg-core.command.database_update.output_query_factory']);
    }

    public function testQueryFormatter()
    {
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Query\QueryFormatterInterface', $this->container['itkg-core.command.database_update.query_formatter']);
    }

    public function testOutputQueryDisplay()
    {
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryDisplay', $this->container['itkg-core.command.database_update.output_query_display']);
    }

    public function testOutputColorQueryDisplay()
    {
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Query\OutputColorQueryDisplay', $this->container['itkg-core.command.database_update.output_color_query_display']);
    }

    public function testSetup()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Setup', $this->container['itkg-core.command.database_update.setup']);
    }

    public function testCommand()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdateCommand', $this->container['itkg-core.command.database_update']);
    }

    public function testMigrationFactory()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Migration\Factory', $this->container['itkg-core.command.database_update.migration_factory']);
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
