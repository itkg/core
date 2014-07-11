<?php

namespace Itkg\Core\Model;

use Itkg\Core\Provider\ServiceProviderInterface;
use Pimple;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceContainer extends Pimple
{
    public function __construct(ConfigInterface $config)
    {
        $this['config'] = $config;
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
