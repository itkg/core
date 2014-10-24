<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core;

use Itkg\Core\Application;
use Itkg\Core\Config;
use Itkg\Core\ServiceContainer;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->app = new Application(Application::ENV_DEV);
    }

    /**
     * Test container
     */
    public function testContainer()
    {
        $this->assertNull($this->app->getContainer());
        $config = new Config();
        $container = new ServiceContainer();
        $application = $this->app->setContainer($container); /* return $this */
        $this->assertEquals($application, $this->app);
        $this->assertEquals($container, $this->app->getContainer());

    }

    /**
     * Test config
     */
    public function testConfig()
    {
        $this->assertNull($this->app->getConfig());
        $config = new Config();
        $application = $this->app->setConfig($config); /* return $this */
        $this->assertEquals($application, $this->app);
        $this->assertEquals($config, $this->app->getConfig());
    }

    /**
     * Test env
     */
    public function testEnv()
    {
        $this->assertTrue($this->app->isDev());
        $this->assertEquals(Application::ENV_DEV, $this->app->getEnv());
        $this->app = new Application(Application::ENV_PREPROD);
        $this->assertFalse($this->app->isDev());
    }
}
