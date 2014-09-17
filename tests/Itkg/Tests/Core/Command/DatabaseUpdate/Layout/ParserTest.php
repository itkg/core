<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Layout;

use Itkg\Core\Command\DatabaseUpdate\Layout\Parser;
use Itkg\Core\Command\DatabaseUpdate\Query;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testNoLayoutExist()
    {
        $parser = new Parser();
        $parser->setPath(__DIR__);

        $queryCreate = new Query('CREATE TABLE MY_TABLE');

        $queryIns = new Query('INSERT INTO MY_TABLE (FIELD) VALUES (VALUE)');

        $parser->parse(array($queryIns, $queryCreate));

        $this->assertEquals($queryIns.$queryCreate, $parser->getContent());
    }

    public function testLayout()
    {
        $parser = new Parser();
        $parser->setPath(TEST_BASE_DIR.'/data/templates/layout.php');

        $queryCreate = new Query('CREATE TABLE MY_TABLE');

        $queryGrant = new Query('GRANT SELECT,INSERT,UPDATE,DELETE ON MY_TABLE TO MY_USER');

        $result = <<<EOF
This is my template

CREATE TABLE MY_TABLE

Grant query :GRANT SELECT,INSERT,UPDATE,DELETE ON MY_TABLE TO MY_USER
EOF;
        $parser->parse(array($queryGrant, $queryCreate));

        $this->assertEquals($result, $parser->getContent());
    }
}
