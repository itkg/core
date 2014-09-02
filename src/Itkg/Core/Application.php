<?php

namespace Itkg\Core;

class Application implements ApplicationInterface
{
    /**
     * Environments
     */
    const ENV_DEV     = 'DEV';
    const ENV_PREPROD = 'PREPROD';
    const ENV_RT      = 'RT';
    const ENV_PROD    = 'PROD';

    /**
     * @var string
     */
    private $env;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var ServiceContainer
     */
    private $container;

    /**
     * Constructor
     *
     * @param string $env
     */
    public function __construct($env = 'PROD')
    {
        $this->env = strtoupper($env);
    }

    /**
     * Set Config
     *
     * @param  ConfigInterface      $config
     * @return ApplicationInterface
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get Config
     *
     * @return ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set container
     *
     * @param  ServiceContainer     $container
     * @return ApplicationInterface
     */
    public function setContainer(ServiceContainer $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Get container
     *
     * @return ServiceContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Is dev
     *
     * @return bool
     */
    public function isDev()
    {
        return $this->env == self::ENV_DEV;
    }

    /**
     * Type env
     *
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }
}
