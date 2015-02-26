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
     *
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
     *
     * @return mixed
     */
    public function setContainer(ServiceContainer $container);
}
