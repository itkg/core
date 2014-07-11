<?php

namespace Itkg\Core\Command\Script;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $loader = $this->createLoader();
        $loader->load(__DIR__.'/../../../../mock/script/ticket.php');
        $this->assertEquals(2, count($loader->getQueries()));

        $loader->load(__DIR__.'/../../../../mock/script/ticket.php');
        $this->assertEquals(2, count($loader->getQueries())); /* Reset queries before load */
    }

    public function testAddQuery()
    {
        $query = 'A new SQL query';
        $loader = $this->createLoader();
        $loader->addQuery($query);
        $this->assertEquals(array($query), $loader->getQueries());
    }

    public function testAddQueryBuilder()
    {
        $loader = $this->createLoader();
        $qb = $loader->getQueryBuilder();
        $qb
            ->select('u.id', 'u.name')
            ->from('users', 'u');
        $loader->addQueryFromBuilder($qb);
        $query = 'SELECT u.id, u.name FROM users u';
        $this->assertEquals(array($query), $loader->getQueries());
    }

    public function testQueryBuilder()
    {
        $this->assertInstanceOf('Doctrine\DBAL\Query\QueryBuilder', $this->createLoader()->getQueryBuilder());
    }

    private function createLoader()
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

        return new Loader($connection);
    }
} 