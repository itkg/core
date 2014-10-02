<?php

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testRegister()
    {
        $container = $this->createContainer();
        $this->assertInstanceOf('\Pimple', $container['cache']);

        $this->assertInstanceOf('Itkg\Core\Cache\Factory', $container['cache']['factory']);
        $this->assertInstanceOf('Itkg\Core\Cache\Listener\CacheListener', $container['cache']['listener']);
    }


    public function createContainer()
    {
        $container = new \Itkg\Core\ServiceContainer();
        $container->register(new \Itkg\Core\Provider\ServiceProvider());

        $container->register(new \Itkg\Core\Cache\Provider\ServiceProvider());

        $container['cache']['adapters'] = array(
            'redis' => 'Itkg\Core\Cache\Adapter\Redis'
        );

        $config = new \Itkg\Core\Config();
        $config['cache'] = array(
            'adapter' => 'redis',
            'redis' => array(
                'host' => 'localhost',
                'port' => 6379
            )
        );

        return $container->setConfig($config);
    }
}
