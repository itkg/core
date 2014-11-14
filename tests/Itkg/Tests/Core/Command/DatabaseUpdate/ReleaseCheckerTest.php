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

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ReleaseCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $checker = $this->getMock('Itkg\Core\Command\DatabaseUpdate\ReleaseChecker', array('checkScripts', 'checkScript'));

        $checker->expects($this->once())->method('checkScripts');
        $checker->expects($this->once())->method('checkScript');

        $locator = new Locator();
        $locator->setParams(array(
            'path' => TEST_BASE_DIR,
            'release' => 'data',
        ));
        
        $checker->check($locator->findScripts(), $locator->findRollbackScripts());
    }
}
