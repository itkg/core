<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query\Decorator;
use Itkg\Core\Command\DatabaseUpdate\Query\Parser;
use Itkg\Core\Command\DatabaseUpdate\Template\Loader;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class DecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDecorateAll()
    {
        $queries = array(
            'DELETE FROM MY_DELETE_TABLE',
            'INSERT INTO MY_INSERT_TABLE (FIELD_ONE) VALUES (VALUE)',
            'CREATE TABLE MY_CREATE_TABLE (FIELD INT)'
        );

        $decoratedQueries = $this->createDecorator()->decorateAll($queries);

        $result = array(
            'DELETE FROM MY_DELETE_TABLE',
            'POST_DELETE_TEMPLATE MY_DELETE_TABLE',
            'PRE_INSERT_TEMPLATE MY_INSERT_TABLE',
            'INSERT INTO MY_INSERT_TABLE (FIELD_ONE) VALUES (VALUE)',
            'PRE_CREATE_TEMPLATE MY_CREATE_TABLE',
            'CREATE TABLE MY_CREATE_TABLE (FIELD INT)',
            'POST_CREATE_TEMPLATE MY_CREATE_TABLE'
        );

        $this->assertEquals($result, $decoratedQueries);
    }

    public function testDecorate()
    {
        $decorator = $this->createDecorator();

        $queries = $decorator->decorate('CREATE OR REPLACE TABLE MY_TABLE (MY_FIELD INT)');

        $result = array(
            'PRE_CREATE_TEMPLATE MY_TABLE',
            'CREATE OR REPLACE TABLE MY_TABLE (MY_FIELD INT)',
            'POST_CREATE_TEMPLATE MY_TABLE'
        );

        $this->assertEquals($result, $queries);
    }

    private function createDecorator()
    {
        $decorator = new Decorator(new Loader(), new Parser());
        $decorator->setTemplatePath(TEST_BASE_DIR.'/data/templates');

        return $decorator;
    }
} 