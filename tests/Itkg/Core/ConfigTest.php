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
    protected $config;

    /**
     * @var Application
     */
    protected $application;

    protected function setUp()
    {
        $this->application = new Application();

        $this->config = new Config(array(
            __DIR__.'/../../data/config/config.yml'
        ));

        $this->application->setConfig($this->config);
    }

    public function testGetSetValue()
    {
        // Explicit isset
        $this->assertFalse($this->config->has('nonexistent'));
        $this->assertTrue($this->config->has('bar'));

        // ArrayAccess isset
        $this->assertFalse(isset($this->config['you']));
        $this->assertTrue(isset($this->config['foo']));

        // Explicit getter
        $this->assertEquals($this->config->get('bar'), 'foo');
        // ArrayAccess getter
        $this->assertEquals($this->config->get('bar'), $this->config['bar']);

        // ArrayAccess setter
        $this->config['new'] = 'value';
        $this->assertEquals($this->config->get('new'), 'value', 'ArrayAccess set does not set value correctly');

        // Multi level ArrayAccess setter
        $this->config['ONE']['TWO'] = 'three';
        $this->assertEquals('three', $this->config['ONE']['TWO']);

        // Explicit setter
        $this->config->set('anotherOne', 'dummy');
        $this->assertEquals($this->config->get('anotherOne'), 'dummy', 'config::set() set does not set value correctly');

        // ArrayAccess on non existing key returns null
        $this->assertNull($this->config['WTF']);

        // Get all values
        $this->assertInternalType('array', $this->config->all());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNoValueException()
    {
        $this->config->offsetUnset('foo');
        $this->config->get('foo');
    }

    /**
     * Test has key
     */
    public function testOffsetExistedKey()
    {

    }
}
