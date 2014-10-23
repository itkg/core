<?php

namespace Itkg\Core\Cache;

use Itkg\Core\Cache\InvalidConfigurationException;
use Itkg\Core\ConfigInterface;

/**
 * Cache adapter factory
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Factory
{
    /**
     * Cache adapters (key => Adapter Class)
     *
     * @var array
     */
    private $adapters;

    /**
     * Constructor
     *
     * @param array $adapters
     */
    public function __construct(array $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * Create an adapter
     *
     * @param  string $adapterType
     *
     * @return AdapterInterface
     *
     * @throws \InvalidArgumentException
     */

    public function create($adapterType, array $config)
    {
        if (!array_key_exists($adapterType, $this->adapters)) {
            throw new \InvalidArgumentException(
                sprintf('Cache Adapter\'s key %s does not exist', $adapterType)
            );
        }

        /**
         * @fixme : Active this part & clean cache config
         */
        if (!isset($config[$adapterType])) {
            throw new InvalidConfigurationException(
                sprintf('Config is not set for adapter %s', $adapterType)
            );
        }

        return new $this->adapters[$adapterType]($config[$adapterType]);
    }
}
