<?php

namespace Itkg\Core\Provider\Command;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Model\Application;
use Itkg\Core\Model\Config;
use Itkg\Core\Model\ServiceContainer;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ScriptCommandProviderTest extends \PHPUnit_Framework_TestCase
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
        $this->container = new Servicecontainer(new Config(new Application()));
        $this->container->register(new ScriptCommandProvider());
    }

    public function testLoader()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\Script\LoaderInterface', $this->container['itkg-core.command.script.loader']);
    }

    public function testRunner()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\Script\RunnerInterface', $this->container['itkg-core.command.script.runner']);
    }

    /**
     * @expectedException \Exception
     */
    public function testDoctrineDependency()
    {
        $loader = $this->container['itkg-core.command.script.loader'];
    }

    public function testOutputQueryFactory()
    {
        $this->assertInstanceOf('Itkg\Core\Command\Script\Query\OutputQueryFactory', $this->container['itkg-core.command.script.output_query_factory']);
    }

    public function testQueryFormatter()
    {
        $this->assertInstanceOf('Itkg\Core\Command\Script\Query\QueryFormatterInterface', $this->container['itkg-core.command.script.query_formatter']);
    }

    public function testOutputQueryDisplay()
    {
        $this->assertInstanceOf('Itkg\Core\Command\Script\Query\OutputQueryDisplay', $this->container['itkg-core.command.script.output_query_display']);
    }

    public function testOutputColorQueryDisplay()
    {
        $this->assertInstanceOf('Itkg\Core\Command\Script\Query\OutputColorQueryDisplay', $this->container['itkg-core.command.script.output_color_query_display']);
    }

    public function testSetup()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\Script\Setup', $this->container['itkg-core.command.script.setup']);
    }

    public function testCommand()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\ScriptCommand', $this->container['itkg-core.command.script']);
    }

    public function testMigrationFactory()
    {
        $this->loadDoctrine();
        $this->assertInstanceOf('Itkg\Core\Command\Script\Migration\Factory', $this->container['itkg-core.command.script.migration_factory']);
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
