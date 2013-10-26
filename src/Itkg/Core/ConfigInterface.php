<?php

namespace Itkg\core;

/**
 * Interface ConfigInterface
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
interface ConfigInterface
{
    /**
     * Add parameter to the list
     * 
     * @param string $key   Key parameter
     * @param mixed  $value  Value parameter
     */
    public function addParam($key, $value);

    /**
     * Get parameter from list
     * 
     * @param string $key   Parameter key
     * 
     * @return mixed        Parameter value
     */
    public function getParam($key);

    /**
     * Get params
     * 
     * @return array List of parameters
     */
    public function getParams();

    /**
     * Override current list of parameters with new ones
     * 
     * @param array  $params List of parameters
     */
    public function mergeParams(array $params = array());

    /**
     * Set existing parameter
     * 
     * @param string $key   Key parameter
     * @param mixed $value  Value parameter
     */
    public function setParam($key, $value);

    /**
     * Set params
     * 
     * @param array $params List of parameters
     */
    public function setParams(array $params = array());

    /**
     * Validate parameters
     */
    public function validateParams();
}