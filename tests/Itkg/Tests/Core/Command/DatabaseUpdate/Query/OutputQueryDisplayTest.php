<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Query\QueryFormatter;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class OutputQueryDisplayTest extends \PHPUnit_Framework_TestCase
{
    public function testDisplay()
    {
        $outputQuery = $this->createOutputQuery();

        $query = <<<EOF
CREATE TABLE MYC_TEST_SCRIPT (
TEST_SCRIPT_ID INT,
TEST_NAME varchar(255)
)
EOF;

        $outputQuery->display($query);

        $this->assertEquals('CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT,TEST_NAME varchar(255));'.PHP_EOL, $outputQuery->getOutput()->output);
    }

    public function testDisplayAll()
    {
        $outputQuery = $this->createOutputQuery();

        $query = <<<EOF
CREATE TABLE MYC_TEST_SCRIPT (
TEST_SCRIPT_ID INT,
TEST_NAME varchar(255)
)
EOF;
        $queries = array(
            $query,
            $query
        );

        $outputQuery->displayAll($queries);

        $this->assertEquals(
            'CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT,TEST_NAME varchar(255));'.PHP_EOL.'CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT,TEST_NAME varchar(255));'.PHP_EOL, $outputQuery->getOutput()->output);
    }

    public function testFormatter()
    {
        $outputQuery = $this->createOutputQuery();
        $this->assertEquals($outputQuery, $outputQuery->setFormatter(new QueryFormatter()));
    }

    private function createOutputQuery()
    {
        $factory = new OutputQueryFactory(new QueryFormatter());

        return $factory
            ->create()
            ->setOutput(new Output(ConsoleOutput::VERBOSITY_NORMAL, true, new OutputFormatter()));
    }


}
