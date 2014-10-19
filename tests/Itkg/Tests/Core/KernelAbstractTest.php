<?php

namespace Itkg\Tests\Core;


use Itkg\Core\Application;
use Itkg\Core\Config;
use Itkg\Core\Resolver\ControllerResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;

class KernelAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $stub = $this->getMock(
            'Itkg\Core\KernelAbstract',
            array('loadRouting', 'loadConfig', 'dispatchEvent'),
            array(), '', false
        );
        $stub->expects($this->any())->method('loadConfig')->will($this->returnValue($stub));
        $stub->expects($this->once())->method('dispatchEvent');

        $mockContainer = $this->getMock('Itkg\Core\ServiceContainer');
        $mockContainer->expects($this->any())->method('offsetGet')
            ->will($this->returnCallback(function($key) {
                    if ($key == 'core') {
                        return array(
                            'dispatcher' => new EventDispatcher()
                        );
                    }
                })
            );
        $app = new Application();
        $config = new Config();
        $app->setConfig($config);

        $mockContainer->expects($this->once())->method('setApp')->with($app);
        $mockContainer->expects($this->once())->method('setConfig')->with($app->getConfig());
        $mockContainer->expects($this->once())->method('offsetSet')->with('kernel', $stub);

        $stub->__construct($mockContainer, $app, new ControllerResolver($mockContainer));


    }

} 