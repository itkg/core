<?php

namespace Itkg\Core\Model;

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
}
