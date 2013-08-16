<?php

namespace Lemon;

/**
 * Classe NotFoundException
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
    protected $requiredParams = array();

    /**
     * Get params
     * 
     * @return array List of parameters
     */
    public function getParams()
    {
        if(!is_array($this->params)) {
            $this->params = array();
        }
        return $this->params;
    }

    /**
     * Set params
     * 
     * @param array $params List of parameters
     */
    public function setParams(array $params = array())
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Add parameter to the list
     * 
     * @param string $key   Key parameter
     * @param mixed $value  Value parameter
     */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Override current list of parameters with new ones
     * 
     * @param  array  $params List of parameters
     */
    public function mergeParams(array $params = array())
    {
        $this->setParams(array_merge($this->getParams(), $params));

        return $this;
    }

    /**
     * Validate parameters
     * 
     * @throws \Lemon\Exception\ConfigException
     */
    public function validateParams()
    {
        foreach($this->requiredParams as $key) {
            if(!isset($this->params[$key])) {
                throw new \Lemon\Exception\ConfigException(
                    sprintf('Parameter %s is required for class %s', $key, get_class($this))
                );
            }
        }
    }
}