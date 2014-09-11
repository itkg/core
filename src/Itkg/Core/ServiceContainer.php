<?php

namespace Itkg\Core;

use Itkg\Cache\CacheFactory;
use Itkg\Core\ApplicationInterface;
use Itkg\Core\ConfigInterface;
use Itkg\Core\Provider\ServiceProviderInterface;
use Pimple;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceContainer extends Pimple
{
    /**
     * Config setter
     *
     * @param  ConfigInterface $config
     *
     * @return ServiceContainer
     */
    public function setConfig(ConfigInterface $config)
    {
        $this['config'] = $config;
        return $this;
    }

    /**
     * Application setter
     *
     * @param  ApplicationInterface $app
     *
     * @return ServiceContainer
     */
    public function setApp(ApplicationInterface $app)
    {
        $this['app'] = $app;
        return $this;
    }

    /**
     * Registers a service provider.
     *
     * @param ServiceProviderInterface $provider A ServiceProviderInterface instance
     * @param array $values An array of values that customizes the provider
     *
     * @return static
     */
    public function register(ServiceProviderInterface $provider, array $values = array())
    {
        $provider->register($this);

        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }

        return $this;
    }
}
