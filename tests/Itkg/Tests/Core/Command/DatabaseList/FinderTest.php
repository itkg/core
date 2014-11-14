<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Command\DatabaseList;

use Itkg\Core\Command\DatabaseList\Finder;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class FinderTest extends \PHPUnit_Framework_TestCase
{
    public function testFindAll()
    {
        $finder = new Finder();

        $releases = $finder->setPath(TEST_BASE_DIR.'/data/releases')->findAll();

        $this->assertEquals(array('1.0', '1.1'), $releases);
    }
}
