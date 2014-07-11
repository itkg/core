<?php

namespace Itkg\Core\Model;

class Application implements ApplicationInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ServiceContainer
     */
    private $container;

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
     * Set Config
     *
     * @param ConfigInterface $config
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

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
     * Set container
     *
     * @param ServiceContainer $container
     * @return mixed
     */
    public function setContainer(ServiceContainer $container)
    {
        $this->container = $container;

        return $this;
    }
}
