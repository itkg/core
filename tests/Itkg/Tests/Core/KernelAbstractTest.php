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
        $mockContainer = $this->getMock('Itkg\Core\ServiceContainer');
        $app = new Application();
        $config = new Config();
        $app->setConfig($config);

        $mockContainer->expects($this->any())->method('offsetGet')
            ->will($this->returnCallback(function($key) {
               if ($key == 'core') {
                   return array(
                       'dispatcher' => new EventDispatcher()
                   );
               }
            })
        );
        $mockContainer->expects($this->once())->method('setApp')->with($app);
        $mockContainer->expects($this->once())->method('setConfig')->with($app->getConfig());
        $mockContainer->expects($this->once())->method('offsetSet')->with('kernel');

        $stub = $this->getMock(
            'Itkg\Core\KernelAbstract',
            array('loadRouting', 'loadConfig', 'dispatchEvent'),
            array($mockContainer, $app, new ControllerResolver($mockContainer)), '', false
        );
        $stub->expects($this->any())->method('loadConfig')->will($this->returnValue($stub));
        $stub->expects($this->once())->method('dispatchEvent');

        $stub->__construct($mockContainer, $app, new ControllerResolver($mockContainer));


    }


} 