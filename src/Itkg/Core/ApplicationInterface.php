<?php

namespace Itkg\Core;

use Itkg\Core\ConfigInterface;
use Itkg\Core\ServiceContainer;

/**
 * Interface ApplicationInterface
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
interface ApplicationInterface
{
    /**
     * Get Config
     *
     * @return ConfigInterface
     */
    public function getConfig();

    /**
     * Set Config
     *
     * @param ConfigInterface $config
     * @return mixed
     */
    public function setConfig(ConfigInterface $config);

    /**
     * Get container
     *
     * @return ServiceContainer
     */
    public function getContainer();

    /**
     * Set container
     *
     * @param ServiceContainer $container
     * @return mixed
     */
    public function setContainer(ServiceContainer $container);
}
