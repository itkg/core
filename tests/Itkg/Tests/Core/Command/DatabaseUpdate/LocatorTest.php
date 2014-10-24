<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Command\DatabaseUpdate;

use Itkg\Core\Command\DatabaseUpdate\Locator;

class LocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testFindScriptsWithScriptName()
    {
        $locator = new Locator();

        $locator->setParams(array(
            'path' => TEST_BASE_DIR,
            'release' => 'data',
            'scriptName' => 'ticket'
        ));

        $this->assertEquals(1, sizeof($locator->findScripts()));

    }

    public function testFindScriptsWithUnvalidScriptName()
    {
        $locator = new Locator();

        $locator->setParams(array(
            'path' => TEST_BASE_DIR,
            'release' => 'data',
            'scriptName' => 'none'
        ));

        $this->assertEmpty($locator->findScripts());
    }
}