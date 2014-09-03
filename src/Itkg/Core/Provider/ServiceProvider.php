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
     * @param \Pimple $mainContainer An Container instance
     */
    public function register(\Pimple $mainContainer)
    {
        $container = new \Pimple();
        $container['dispatcher'] = $mainContainer->share(function () {
            $dispatcher = new EventDispatcher();
            // Add listeners

            return $dispatcher;
        });

        $container['db'] = $mainContainer->share(function () {
            return \Pelican_Db::getInstance();
        });

        $mainContainer['core'] = $container;
    }
}
