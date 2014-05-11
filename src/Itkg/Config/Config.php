<?php

namespace Itkg\Config;

use Itkg\Config\Exception\ConfigException;
use Itkg\Config\Exception\NotFoundException;

/**
 * Class NotFoundException
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class Config implements ConfigInterface
{
    /**
     * List of parameters
     * 
     * @var array
     */
    protected $params;
    /**
     * List of required parameters (for validation)
     * 
     * @var array
     */
    protected $requiredParams;

    /**
     * Get parameter from list
     *
     * @param string $key   Parameter key
     *
     * @throws Exception\NotFoundException
     * @return mixed        Parameter value
     */
    public function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        throw new NotFoundException(
            sprintf(
                'Parameter %s is does not exist for class %s', 
                $key, 
                get_class($this)
            )
        );
    }

    /**
     * Get params
     * 
     * @return array List of parameters
     */
    public function getParams()
    {
        if (!is_array($this->params)) {
            $this->params = array();
        }
        return $this->params;
    }

    /**
     * Set params
     *
     * @param array $params List of parameters
     * @return $this
     */
    public function setParams(array $params = array())
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get requiredParams
     *
     * @return array List of required parameters
     */
    public function getRequiredParams()
    {
        if (!is_array($this->requiredParams)) {
            $this->requiredParams = array();
        }
        return $this->requiredParams;
    }

    /**
     * Set Config required params
     *
     * @param array $requiredParams List of required parameters
     */
    public function setRequiredParams(array $requiredParams = array())
    {
        $this->requiredParams = $requiredParams;
    }

    /**
     * Check if param exist
     *
     * @param $key
     * @return bool
     */
    public function hasParam($key)
    {
        return isset($this->params[$key]);
    }

    /**
     * Set existing parameter
     *
     * @param string $key   Key parameter
     * @param mixed  $value  Value parameter
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Add parameter to the list
     *
     * @param string $key   Key parameter
     * @param mixed  $value  Value parameter
     *
     * @return \Itkg\Config
     */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Override current list of parameters with new ones
     *
     * @param array $params List of parameters
     *
     * @return \Itkg\Config
     */
    public function mergeParams(array $params = array())
    {
        $this->setParams(array_merge($this->getParams(), $params));

        return $this;
    }

    /**
     * Validate parameters
     * 
     * @throws ConfigException
     */
    public function validateParams()
    {
        foreach ($this->getRequiredParams() as $key) {
            if (!isset($this->params[$key])) {
                throw new ConfigException(
                    sprintf('Parameter %s is required for class %s', $key, get_class($this))
                );
            }
        }
    }
}