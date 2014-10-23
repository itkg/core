<?php

namespace Itkg\Core;

use Itkg\Core\ConfigInterface;
use Itkg\Core\YamlLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Parser as YamlParser;

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
     * @param array $files YAML config files to load
     */
    public function __construct(array $files = array())
    {
        $this->load($files);
    }

    /**
     * Load config files
     *
     * @param array $files
     */
    public function load(array $files = array())
    {
        $loader = new YamlLoader(new FileLocator, new YamlParser);

        foreach ($files as $file) {
            $configValues = $loader->load($file);

            $configValues = $this->loadImports($loader, $file, $configValues);

            $this->params = array_replace_recursive($this->params, $configValues);
        }
    }

    /**
     * Get all config values
     *
     * @return array
     */
    public function all()
    {
        return $this->params;
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
     * @param  $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->params[$key];
        }

        throw new \InvalidArgumentException(sprintf("Config key %s doest not exist", $key));
    }

    /**
     * Set a config value for a key
     *
     * @param  string $key
     * @param  mixed $value
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
     * @param  mixed $offset <p>
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

    /**
     * Load imports
     *
     * @param YamlLoader $loader
     * @param string $file
     * @param array $configValues
     * @return array
     */
    protected function loadImports(YamlLoader $loader, $file, array $configValues = array())
    {
        if (!isset($configValues['imports'])) {
            return $configValues;
        }

        if (!is_array($configValues['imports'])) {
            $configValues['imports'] = array($configValues['imports']);
        }

        foreach ($configValues['imports'] as $import) {
            $loader->setCurrentDir(dirname($file));
            $importValues = $loader->import($import, null, false, $file);

            $configValues = array_replace_recursive($configValues, $importValues);
        }

        return $configValues;
    }
}
