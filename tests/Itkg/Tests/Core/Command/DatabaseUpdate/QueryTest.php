<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Created by PhpStorm.
 * User: pdenis
 * Date: 14/09/14
 * Time: 19:24
 */

namespace Itkg\Tests\Core\Command\DatabaseUpdate;


use Itkg\Core\Command\DatabaseUpdate\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {

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
    
        $query = new Query($createQuery);

        $this->assertEquals('CREATE TABLE MY_TABLE (ID INT)', $query->getValue());
        $this->assertEquals('create_table', $query->getType());
        $this->assertEquals($data, $query->getData());

        $this->assertEquals($query, $query->setValue($insertQuery));
        $this->assertEquals('insert', $query->getType());
        $this->assertEquals($data, $query->getData());

        $query->setValue($updateQuery);
        $this->assertEquals('update', $query->getType());
        $this->assertEquals($data, $query->getData());

        $query->setValue($deleteQuery);
        $this->assertEquals('delete', $query->getType());
        $this->assertEquals($data, $query->getData());

        $query->setValue($dropQuery);
        $this->assertEquals('drop_table', $query->getType());
        $this->assertEquals($data, $query->getData());

        $query->setValue($createSequence);
        $this->assertEquals('create_sequence', $query->getType());
        $this->assertEquals(array('identifier' => 'MY_SEQ'), $query->getData());

        $query->setValue($createSynonym);
        $this->assertEquals('create_synonym', $query->getType());
        $this->assertEquals(array('identifier' => 'MY_SYNONYM'), $query->getData());

        $query->setValue($createIndex);
        $this->assertEquals('create_index', $query->getType());
        $this->assertEquals(array('identifier' => 'MY_INDEX'), $query->getData());

        $query->setValue($grant);
        $this->assertEquals('grant', $query->getType());

        /**
         * @TODO : Grant parse
         */
        // $this->assertEquals(array('identifier' => 'MY_INDEX'), $query->getData());
    }
} 