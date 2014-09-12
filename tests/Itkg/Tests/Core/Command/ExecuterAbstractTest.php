<?php

namespace Itkg\Tests\Core\Command;

use Itkg\Core\Command\Formatter\CommandLineFormatter;
use Itkg\Tests\Core\Command\DatabaseUpdate\Query\Output;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ExecuterAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $formatter = new CommandLineFormatter('%msg%');
        $record = array(
            'extra' => array()
        );
        $stub = $this->getMockForAbstractClass('Itkg\Core\Command\CommandExecuterAbstract', array(
            'name',
            $formatter,
            $record
        ));

        $exception = new \Exception('My exception');
        $stub->expects($this->any())
            ->method('doExecute')
            ->will($this->throwException($exception));

        $output = new Output();
        $stub->execute(new ArgvInput(), $output);

        $this->assertEquals($formatter->formatException($exception, $record).PHP_EOL, $output->output);

    }
}
