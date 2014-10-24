<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Migration;

use Itkg\Core\Command\DatabaseUpdate\Migration\Factory;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateMigration()
    {
        $factory = new Factory();
        $this->assertInstanceOf('Itkg\Core\Command\DatabaseUpdate\Migration', $factory->createMigration(array(), array()));
    }
}
