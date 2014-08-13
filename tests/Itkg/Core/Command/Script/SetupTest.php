<?php

namespace Itkg\Core\Command\Script;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Command\Script\Migration\Factory;

class SetupTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistedScript()
    {
        $setup = $this->createSetup();
        $setup->createMigration('unknown/script.php', __DIR__.'/../../../../mock/rollback/ticket.php');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistedRollbackScript()
    {
        $setup = $this->createSetup();
        $setup->createMigration(__DIR__.'/../../../../mock/script/ticket.php', 'unknown/script.php');
    }

    public function testForcedRollback()
    {
        $setup = $this->createSetup();

        $this->assertFalse($setup->getForcedRollback());
        $this->assertEquals($setup, $setup->setForcedRollback(true));
        $this->assertTrue($setup->getForcedRollback());
    }

    public function testExecuteQueries()
    {
        $setup = $this->createSetup();

        $this->assertEquals($setup, $setup->setExecuteQueries(true));
    }

    public function testRollbackFirst()
    {
        $setup = $this->createSetup();

        $this->assertFalse($setup->getRollbackedFirst());
        $this->assertEquals($setup, $setup->setRollbackedFirst(true));
        $this->assertTrue($setup->getRollbackedFirst());
    }

    public function testRun()
    {
        $setup = $this->createSetup();

        $setup->getLocator()->setParams(array(
            'path'    => __DIR__.'/../../../../',
            'release' => 'mock'
        ));

        $setup->run();

        $displayed = array(
            'CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT, TEST_NAME varchar(255))',
            'CREATE TABLE MYC_TEST_SCRIPT2 (TEST_SCRIPT_ID INT, TEST_NAME varchar(255))'
        );

        $this->assertEquals($displayed, $setup->getQueries());
    }

    public function testCreateMigration()
    {
        $script = __DIR__.'/../../../../mock/script/ticket.php';
        $rollbackScript = $script = __DIR__.'/../../../../mock/rollback/ticket.php';

        $setup = $this->createSetup();

        $this->assertEquals($setup, $setup->createMigration($script, $rollbackScript));
    }

    private function createSetup()
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
        return new Setup($runner, $loader, $factory, $locator);
    }
} 