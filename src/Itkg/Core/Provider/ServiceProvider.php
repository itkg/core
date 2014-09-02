<?php

namespace Itkg\Core\Provider;


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
        $container['core']['dispatcher'] = $this->share(function () {
            $dispatcher = new EventDispatcher;
            // Add listeners

            return $dispatcher;
        });

        $container['core']['db'] = $this->share(function() {
            return \Pelican_Db::getInstance();
        });
    }
}
