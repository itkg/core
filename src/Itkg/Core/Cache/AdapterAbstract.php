<?php

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
