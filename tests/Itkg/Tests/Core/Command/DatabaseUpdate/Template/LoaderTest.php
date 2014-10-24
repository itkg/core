<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Template;

use Itkg\Core\Command\DatabaseUpdate\Template\Loader;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $loader = new Loader();

        $loader->load(TEST_BASE_DIR.'/data/templates/pre_create_table_template.php', array('identifier' => 'MY_TABLE'));

        $this->assertEquals(array('PRE_CREATE_TEMPLATE MY_TABLE'), $loader->getQueries());
    }
}
