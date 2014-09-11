<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class QutputQueryFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testDefaultCreate()
    {
        $factory = $this->createFactory();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryDisplay', $factory->create());
    }

    public function testColorCreate()
    {
        $factory = $this->createFactory();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Query\OutputColorQueryDisplay', $factory->create('color'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUndefinedCreate()
    {
        $factory = $this->createFactory();
        $factory->create('undefined');
    }

    private function createFactory()
    {
        return new OutputQueryFactory(new Formatter());
    }
}
