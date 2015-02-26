<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param ServiceProviderInterface $provider  A ServiceProviderInterface instance
     * @param array                    $values    An array of values that customizes the provider
     *
     * @return ServiceContainer
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
     * Load config
     *
     * @param ConfigInterface $config
     */
    protected function loadConfig(ConfigInterface $config)
    {

    }

    /**
     * Load application
     *
     * @param ApplicationInterface $app
     */
    protected function loadApp(ApplicationInterface $app)
    {

    }
}
