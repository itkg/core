<?php

namespace Itkg\Core;

use Itkg\Core\ServiceContainer;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class EntityAbstract implements CollectionableInterface
{
    /**
     * Property prefix used in arrayAccess & database columns
     */
    const PROPERTY_PREFIX = '';

    /**
     * ServiceContainer instance
     * @var ServiceContainer;
     */
    protected $container;

    /**
     * Repository
     * @var RepositoryAbstract
     */
    protected $repository;

    /**
     * Event dispatcher instance
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Loaded flag
     * @var bool
     */
    private $isLoaded = false;

    /**
     * Excluded properties
     *
     * @var array
     */
    protected $excludedPropertiesForCache = array(
        'container',
        'dispatcher',
        'repository',
        'excludedPropertiesForCache'
    );

    /**
     * Entity ID
     *
     * @var mixed
     */
    protected $id;

    /**
     * Id setter
     *
     * @param mixed $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Id getter
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Data setter
     *
     * @param  array $values
     *
     * @return $this
     */
    public function setDataFromArray(array $values = array())
    {
        foreach ($values as $key => $value) {

            $desiredKey = $this->trimPrefixKey($key);

            $propertyName = $this->getPropertyName($desiredKey);
            $setterMethodName = 'set' . ucfirst($propertyName);

            // Direct setter exists into object, just use it
            if (method_exists($this, $setterMethodName) && $value !== null) {
                $this->$setterMethodName($value);
            } elseif ($value !== null) {
                // Otherwise, try to get sub object to set data
                $this->setDataForSubEntitiy($desiredKey, $value);
            }
        }
        return $this;
    }

    /**
     * Process sub entity to set data recursively
     *
     * @param string $propertyKey
     * @param mixed $value
     *
     * @return void
     */
    private function setDataForSubEntitiy($propertyKey, $value)
    {
        $subKey = strtolower(strstr($propertyKey, '_', true));
        $getterMethodName = 'get' . ucfirst($subKey);

        if (!empty($subKey) && method_exists($this, $getterMethodName)) {
            $subObject = $this->$getterMethodName();

            // If we managed to get sub object, set his data
            if ($subObject instanceof EntityAbstract) {
                $subObject->setDataFromArray(
                    array($propertyKey => $value)
                );
            }
        }
    }

    /**
     * Get cache TTL
     *
     * @implements \Itkg\CacheableInterface
     *
     * @return int
     */
    public function getTtl()
    {
        return 86400; // One day cache
    }

    /**
     * Return if object is already loaded from cache
     *
     * @implements \Itkg\CacheableInterface
     *
     * @return bool
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * Is loaded flag setter
     *
     * @param  bool $isLoaded
     * @return $this
     */
    public function setIsLoaded($isLoaded = true)
    {
        $this->isLoaded = (bool)$isLoaded;
        return $this;
    }

    /**
     * Get camelCase property name from old falshioned upper case name
     *
     * PAGE_ID => id with PROPERTY_PREFIX set to PAGE
     * MEDIA_DISPLAY_TYPE => displayType with PROPERTY_PREFIX set to MEDIA
     *
     * @param  string $propName
     *
     * @return string
     */
    protected function getPropertyName($propName)
    {
        return lcfirst(
            str_replace(
                ' ',
                '',
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        strtolower($propName)
                    )
                )
            )
        );
    }

    /**
     * Get method name for getter
     *
     * @param  string $propName
     * @return string
     */
    protected function getPropertyValue($propName)
    {
        $desiredKey = $this->trimPrefixKey($propName);

        $getter = 'get' . ucfirst($this->getPropertyName($desiredKey));
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        // Otherwise, try to get sub object to get data
        $subKey = strtolower(strstr($desiredKey, '_', true));
        $getterMethodName = 'get' . ucfirst($subKey);

        $subObject = $this->$getterMethodName();
        if ($subObject instanceof EntityAbstract) {
            return $subObject->getPropertyValue($desiredKey);
        }

        throw new \InvalidArgumentException(
            sprintf(
                'No getter set for %s (getter was %s:%s)',
                $propName,
                get_called_class(),
                $getter
            )
        );
    }

    /**
     * Process key by removing prefix
     *
     * @param  string $key
     * @return string
     */
    protected function trimPrefixKey($key)
    {
        return preg_replace('/^' . static::PROPERTY_PREFIX . '_/', '', $key, 1);
    }

    /**
     * Repository getter
     *
     * @return RepositoryAbstract
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Get data from entity for cache set
     *
     * @return mixed
     */
    public function getDataForCache()
    {
        $reflectionClass = new \ReflectionObject($this);
        $array = array();
        $excludes = array_flip($this->excludedPropertiesForCache);
        $excludes['excludedPropertiesForCache'] = true;
        foreach ($reflectionClass->getProperties() as $property) {
            $propName = $property->getName();
            if (isset($excludes[$propName])) {
                continue;
            }

            $value = $this->$propName;

            if ($value instanceof CacheableInterface) {
                $value = $value->getDataForCache();
            } elseif (is_object($value)) {
                $value = null;
            }

            if (null !== $value) {
                $array[$propName] = $value;
            }
        }

        if (empty($array)) {
            return null;
        }
        return json_encode($array);

    }

    /**
     * @param $data
     * @return $this
     */
    public function setDataFromCache($data)
    {
        if (is_scalar($data)) {
            $data = json_decode($data, true);
        }

        if (is_array($data)) {
            foreach ($data as $property => $value) {
                if ($this->$property instanceof CacheableInterface) {
                    $this->$property->setDataFromCache($value);
                } elseif (!is_object($this->$property)) {
                    $this->$property = $value;
                }
            }
        }

        return $this;
    }
}
