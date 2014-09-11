<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query\Parser;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $this->parser = new Parser();

        $createQuery = 'CREATE TABLE MY_TABLE (ID INT)';
        $insertQuery = 'INSERT INTO MY_TABLE (FIELD_ONE) VALUES (FIELD_ONE_VALUE)';
        $updateQuery = 'UPDATE MY_TABLE SET FIELD_ONE = FIELD_ONE_VALUE';
        $deleteQuery = 'DELETE FROM MY_TABLE WHERE FIELD_ONE = FIELD_ONE_VALUE';
        $dropQuery   = 'DROP TABLE MY_TABLE';
        $selectQuery = 'SELECT * FROM MY_TABLE';

        $data = array('table_name' => 'MY_TABLE');

        $this->parser->parse($createQuery);
        $this->assertEquals('create', $this->parser->getType());
        $this->assertEquals($data, $this->parser->getData());

        $this->parser->parse($insertQuery);
        $this->assertEquals('insert', $this->parser->getType());
        $this->assertEquals($data, $this->parser->getData());

        $this->parser->parse($updateQuery);
        $this->assertEquals('update', $this->parser->getType());
        $this->assertEquals($data, $this->parser->getData());

        $this->parser->parse($deleteQuery);
        $this->assertEquals('delete', $this->parser->getType());
        $this->assertEquals($data, $this->parser->getData());

        $this->parser->parse($dropQuery);
        $this->assertEquals('drop', $this->parser->getType());
        $this->assertEquals($data, $this->parser->getData());

        $this->parser->parse($selectQuery);
        $this->assertEquals('select', $this->parser->getType());
        $this->assertEquals($data, $this->parser->getData());
    }
}
