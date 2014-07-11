<?php

namespace Itkg\Core\Provider;


/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple $container An Container instance
     */
    public function register(\Pimple $container);
} 