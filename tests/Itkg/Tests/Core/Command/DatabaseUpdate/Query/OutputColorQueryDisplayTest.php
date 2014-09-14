<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;
use Itkg\Core\Command\DatabaseUpdate\Query;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class OutputColorQueryDisplayTest extends \PHPUnit_Framework_TestCase
{
    public function testChangeStyle()
    {
        $display = $this->createOutputQuery();

        $formatter = new Formatter();
        $query = new Query('CREATE TABLE MY_TABLE');

        $display->display($query);

        $this->assertNotNull($display->getOutput()->getFormatter()->getStyle('create_table'));

        $query = new Query('INSERT INTO MY_TABLE (FIELD) VALUES (VALUE)');

        $display->display($query);
        $this->assertNotNull($display->getOutput()->getFormatter()->getStyle('insert'));
    }

    private function createOutputQuery()
    {
        $factory = new OutputQueryFactory(new Formatter());

        return $factory
            ->create('color')
            ->setOutput(new Output(ConsoleOutput::VERBOSITY_NORMAL, true, new OutputFormatter()));
    }
}
