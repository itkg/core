<?php

namespace Itkg\Core;

use Itkg\Core\ApplicationInterface;
use Itkg\Core\ConfigInterface;
use Itkg\Core\Provider\ServiceProviderInterface;
use Pimple;

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
        $this->loadConfig($config);
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
        $this->loadApp($app);
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

    /**
     * @param ConfigInterface $config
     */
    protected function loadConfig(ConfigInterface $config)
    {

    }

    /**
     * @param ApplicationInterface $app
     */
    protected function loadApp(ApplicationInterface $app)
    {

    }
}
