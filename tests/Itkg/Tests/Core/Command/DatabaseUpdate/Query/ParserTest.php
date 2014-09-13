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
        $createSequence = 'CREATE SEQUENCE MY_SEQ';
        $createSynonym = 'CREATE SYNONYM MY_SYNONYM FOR MY_TABLE';
        $createIndex = 'CREATE INDEX MY_INDEX on MY_TABLE(MY_FIELD)';
        $grant = 'GRANT ALL PRIVILEGES TO MY_USER ON MY_TABLE';

        $data = array('identifier' => 'MY_TABLE');

        $this->parser->parse($createQuery);
        $this->assertEquals('create_table', $this->parser->getType());
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

        $this->parser->parse($createSequence);
        $this->assertEquals('create_sequence', $this->parser->getType());
        $this->assertEquals(array('identifier' => 'MY_SEQ'), $this->parser->getData());

        $this->parser->parse($createSynonym);
        $this->assertEquals('create_synonym', $this->parser->getType());
        $this->assertEquals(array('identifier' => 'MY_SYNONYM'), $this->parser->getData());

        $this->parser->parse($createIndex);
        $this->assertEquals('create_index', $this->parser->getType());
        $this->assertEquals(array('identifier' => 'MY_INDEX'), $this->parser->getData());

        $this->parser->parse($grant);
        $this->assertEquals('grant', $this->parser->getType());

        /**
         * @TODO : Grant parse
         */
       // $this->assertEquals(array('identifier' => 'MY_INDEX'), $this->parser->getData());
    }
}
