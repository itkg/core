<?php

namespace Itkg\Core\Command\Script;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class RunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $queries = array('QUERY 1', 'QUERY 2');
        $rollbackQueries = array('ROLLBACK QUERY 1', 'ROLLBACK QUERY 2');

        $migration = new Migration($queries, $rollbackQueries);

        $runner = $this->createRunner();
        $runner->run($migration);

        $this->assertEquals($queries, $runner->getPlayedQueries());
        $runner->run($migration, false, true);

        $this->assertEquals(array_merge($queries, $queries, $rollbackQueries), $runner->getPlayedQueries());
    }

    private function createRunner()
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

        return new Runner($connection);
    }
} 