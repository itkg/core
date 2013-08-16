<?php

namespace Lemon;

/**
 * Interface ConfigInterface
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
interface ConfigInterface
{
    /**
     * Get params
     * 
     * @return array List of parameters
     */
    public function getParams();

    /**
     * Set params
     * 
     * @param array $params List of parameters
     */
    public function setParams(array $params = array());

    /**
     * Add parameter to the list
     * 
     * @param string $key   Key parameter
     * @param mixed $value  Value parameter
     */
    public function addParam($key, $value);

    /**
     * Override current list of parameters with new ones
     * 
     * @param  array  $params List of parameters
     */
    public function mergeParams(array $params = array());

    /**
     * Validate parameters
     */
    public function validateParams();
}