<?php

namespace Itkg\Core;

use Itkg\Core\Application;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Application();
    }

    /**
     * Test container
     */
    public function testContainer()
    {
        $this->assertNull($this->object->getContainer());
        $config = new Config();
        $container = new ServiceContainer();
        $application = $this->object->setContainer($container); /* return $this */
        $this->assertEquals($application, $this->object);
        $this->assertEquals($container, $this->object->getContainer());

    }

    /**
     * Test config
     */
    public function testConfig()
    {
        $this->assertNull($this->object->getConfig());
        $config = new Config();
        $application = $this->object->setConfig($config); /* return $this */
        $this->assertEquals($application, $this->object);
        $this->assertEquals($config, $this->object->getConfig());
    }
}
