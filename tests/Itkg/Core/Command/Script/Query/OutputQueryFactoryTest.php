<?php

namespace Itkg\Core\Command\Script\Query;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class QutputQueryFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testDefaultCreate()
    {
        $factory = $this->createFactory();
        $this->assertInstanceOf('Itkg\Core\Command\Script\Query\OutputQueryDisplay', $factory->create());
    }

    public function testColorCreate()
    {
        $factory = $this->createFactory();
        $this->assertInstanceOf('Itkg\Core\Command\Script\Query\OutputColorQueryDisplay', $factory->create('color'));
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
        return new OutputQueryFactory(new QueryFormatter());
    }
}
