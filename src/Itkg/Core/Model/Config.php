<?php

namespace Itkg\Core\Model;

/**
 * Config handler model class
 */
class Config implements \ArrayAccess, ConfigInterface
{
    /**
     * Params storage
     * @var array
     */
    protected $params = array();

    /**
     * Constructor
     *
     * @param ApplicationInterface $application
     * @param array $configParams
     * @internal param array $files Config files path
     */
    public function __construct(ApplicationInterface $application, array $configParams = array())
    {
        $this->params = array_merge($this->params, $configParams);

        $application->setConfig($this);
    }

    /**
     * Return existence of key
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->params[$key]);
    }

    /**
     * Get a config value for a key
     *
     * @param $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($key)
    {
        if($this->has($key)) {
            return $this->params[$key];
        }

        throw new \InvalidArgumentException(sprintf("Config key %s doest not exist", $key));
    }

    /**
     * Set a config value for a key
     *
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Whether a offset exists
     *
     * @param  mixed    $offset <p>
     * @return boolean  True on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param  mixed $offset
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        return $this->params[$offset];
    }

    /**
     * Offset to set
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset unset
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->has($offset)) {
            unset($this->params[$offset]);
        }
    }
}
