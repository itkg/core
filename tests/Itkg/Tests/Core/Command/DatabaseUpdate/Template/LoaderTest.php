<?php

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

        $loader->load(TEST_BASE_DIR.'/data/templates/pre_create_template.php', array('table_name' => 'MY_TABLE'));

        $this->assertEquals(array('PRE_CREATE_TEMPLATE MY_TABLE'), $loader->getQueries());
    }
}
