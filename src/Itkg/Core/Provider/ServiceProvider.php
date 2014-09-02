<?php

namespace Itkg\Core\Provider;

use Itkg\Core\ServiceContainer;
use Symfony\Component\EventDispatcher\EventDispatcher;


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
        $services['dispatcher'] = $container->share(function () {
            $dispatcher = new EventDispatcher();
            // Add listeners

            return $dispatcher;
        });

        $services['db'] = $container->share(function() {
            return \Pelican_Db::getInstance();
        });

        $container['core'] = $services;
    }
}
