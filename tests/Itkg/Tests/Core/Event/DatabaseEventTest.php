<?php

namespace Itkg\Tests\Core\Event;

/**
 * Class DatabaseEventTest
 */
class DatabaseEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testConstructor()
    {
        $query = 'SELECT * FROM MY_TABLE';
        $data = array(
            'my RESULT SET'
        );
        $executionTime = 0.12;

        $event = new \Itkg\Core\Event\DatabaseEvent($query, $executionTime, $data);

        $this->assertEquals($query, $event->getQuery());
        $this->assertEquals($executionTime, $event->getExecutionTime());
        $this->assertEquals($data, $event->getData());
    }
}
