<?php

namespace Itkg\Core\Model;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Provider\Command\ScriptCommandProvider;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceContainerTest extends \PHPUnit_Framework_TestCase
{
    public function test__construct()
    {
        $config = new Config(new Application());
        $container = new ServiceContainer($config);
        $this->assertEquals($config, $container['config']);
    }

    public function testRegister()
    {
        $config = new Config(new Application());
        $container = new ServiceContainer($config);
        $params = array(
            'dbname' => 'DBNAME',
            'user'   => 'USER',
            'password' => 'PWD',
            'host' => '',
            'driver' => 'oci8'
        );

        $config = new Configuration();
        $connection = DriverManager::getConnection($params, $config);
        $values = array('doctrine.connection' => $connection);

        $container->register(new ScriptCommandProvider(), $values);
        $this->assertEquals($connection, $container['doctrine.connection']);
    }
} 