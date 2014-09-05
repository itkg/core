<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Command\DatabaseUpdate\Migration\Factory;
use Itkg\Core\Command\DatabaseUpdate\Loader;
use Itkg\Core\Command\DatabaseUpdate\Runner;
use Itkg\Core\Command\DatabaseUpdate\Locator;
use Itkg\Core\Command\DatabaseUpdate\Setup;

class SetupTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistedScript()
    {
        $setup = $this->createSetup();
        $setup->createMigration('unknown/script.php', TEST_BASE_DIR.'/data/rollback/ticket.php');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistedRollbackScript()
    {
        $setup = $this->createSetup();
        $setup->createMigration(TEST_BASE_DIR.'/data/script/ticket.php', 'unknown/script.php');
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

        $setup->getLocator()->setParams(array(
            'path'    => TEST_BASE_DIR,
            'release' => 'data'
        ));

        $setup->run();

        $displayed = array(
            'DROP TABLE MYC_TEST_SCRIPT',
            'DROP TABLE MYC_TEST_SCRIPT2'
        );

        $this->assertEquals($displayed, $setup->getQueries());

    }

    public function testRun()
    {
        $setup = $this->createSetup();

        $setup->getLocator()->setParams(array(
            'path'    => TEST_BASE_DIR,
            'release' => 'data'
        ));

        $setup->run();

        $displayed = array(
            'CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT, TEST_NAME varchar(255))',
            'CREATE TABLE MYC_TEST_SCRIPT2 (TEST_SCRIPT_ID INT, TEST_NAME varchar(255))'
        );

        $this->assertEquals($displayed, $setup->getQueries());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRunWithNoScript()
    {
        if (!file_exists(TEST_BASE_DIR.'/data/void')) {
            mkdir(TEST_BASE_DIR.'/data/void');
            mkdir(TEST_BASE_DIR.'/data/void/script');
            mkdir(TEST_BASE_DIR.'/data/void/rollback');
        }

        $setup = $this->createSetup();

        $setup->getLocator()->setParams(array(
            'path'    => TEST_BASE_DIR.'/data',
            'release' => 'void'
        ));

        $setup->run();
    }

    /**
     * @expectedException \LogicException
     */
    public function testRunWithNoRollback()
    {
        if (!file_exists(TEST_BASE_DIR.'/data/uncomplete/rollback')) {
            mkdir(TEST_BASE_DIR.'/data/uncomplete/rollback');
        }

        $setup = $this->createSetup();

        $setup->getLocator()->setParams(array(
            'path'    => TEST_BASE_DIR.'/data',
            'release' => 'uncomplete'
        ));

        $setup->run();

    }
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testRunWithUnvalidPath()
    {
        $setup = $this->createSetup();

        $setup->run();
    }

    public function testCreateMigration()
    {
        $script = TEST_BASE_DIR.'/data/script/ticket.php';
        $rollbackScript = TEST_BASE_DIR.'/data/rollback/ticket.php';

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