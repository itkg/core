<?php

namespace Itkg\Core\Cache\Provider;

use Itkg\Core\Cache\Listener\CacheListener;
use Itkg\Core\Provider\ServiceProviderInterface;
use Itkg\Core\ServiceContainer;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple $container An Container instance
     */
    public function register(\Pimple $container)
    {
        $services = new \Pimple();
        $services['factory'] = $container->share(function ($c) {
            return new \Itkg\Core\Cache\Factory($c['adapters']);
        });

        $services['listener'] = $container->share(function ($c) use ($services, $container) {

            return new CacheListener(
                $services['factory']->create(
                    $container['config']['cache']['adapter'],
                    $container['config']['cache']
                ),
                $container['core']['dispatcher']
            );
        });

        $container['cache'] = $services;
    }
}