<?php

namespace Itkg\Core;

use Itkg\Core\Application;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    protected $object;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var array
     */
    protected $params;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->application = new Application();
        $this->params = array(
            'foo' => 'bar',
            'bar' => 'foo'
        );
        $this->object = new Config(array(
            __DIR__.'/../../data/config/config.yml'
        ));
        $this->application->setConfig($this->object);
    }

    /**
     * Test construct
     */
    public function test__construct()
    {
        $this->assertEquals($this->application->getConfig(), $this->object);

    }

    /**
     * Test get existed key
     */
    public function testGetExistedKey()
    {
        $this->assertEquals($this->params['bar'], $this->object->get('bar'));
    }

    /**
     * Test get existed key
     */
    public function testOffsetGetExistedKey()
    {
        $this->assertEquals($this->params['bar'], $this->object->offsetGet('bar'));
    }

    /**
     * Test offset set
     */
    public function testOffsetSet()
    {
        $this->object->offsetSet('you', 'me');
        $this->assertEquals('me', $this->object->offsetGet('you'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetUnset()
    {
        $this->object->offsetUnset('foo');
        $this->object->get('foo');
    }

    /**
     * Test has key
     */
    public function testHasExistedKey()
    {
        $this->assertFalse($this->object->has('you'));
        $this->assertTrue($this->object->has('foo'));
    }

    /**
     * Test has key
     */
    public function testOffsetExistedKey()
    {
        $this->assertFalse($this->object->offsetExists('you'));
        $this->assertTrue($this->object->offsetExists('foo'));
    }

    /**
     * Test unexisted key
     *
     * @expectedException \InvalidArgumentException
     */
    public function testHasNotExistedKey()
    {
        $value = $this->object->get('WTF');
    }

    /**
     * test set
     */
    public function testSet()
    {
        $this->object->set('you', 'me');
        $this->assertEquals('me', $this->object->get('you'));

        $this->object['ONE']['TWO'] = 'three'; /* Test &offsetGet */
        $this->assertEquals('three', $this->object['ONE']['TWO']);
    }
}
