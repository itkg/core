<?php

namespace Itkg\Core\Command\DatabaseUpdate\Migration;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateMigration()
    {
        $factory = new Factory();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Migration\Factory', $factory);
    }
}
