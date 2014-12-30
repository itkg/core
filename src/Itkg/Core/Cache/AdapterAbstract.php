<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Cache;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
abstract class AdapterAbstract
{
    /**
     * Configuration
     *
     * @var array
     */
    protected $config;

    /**
     * Cache content
     *
     * @var string
     */
    protected $content;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
