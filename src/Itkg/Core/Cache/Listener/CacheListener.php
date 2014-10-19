<?php

namespace Itkg\Core\Cache\Listener;

use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\Event\EntityLoadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Cache event listener
 *
 */
class CacheListener implements EventSubscriberInterface
{
    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * Constructor
     *
     * @param AdapterInterface $cache
     */
    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Load data from cache
     *
     * @param EntityLoadEvent $event
     */
    public function fetchEntityFromCache(EntityLoadEvent $event)
    {
        $entity = $event->getEntity();
        // Check cache
        if (false !== $data = $this->cache->get($entity)) {

            // Set data from cache to entity object
            $entity->setDataFromCache($data);
            $entity->setIsLoaded(true);
        }
    }

    /**
     * Set cache
     *
     * @param EntityLoadEvent $event
     */
    public function setCacheForEntity(EntityLoadEvent $event)
    {
        $entity = $event->getEntity();

        if (!$entity->isLoaded()) {
            $this->cache->set($entity);
        }
    }

    /**
     * Register events and callbacks
     *
     * @implements EventSubscriberInterface
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return array(
            'entity.before.load' => 'fetchEntityFromCache',
            'entity.after.load' => 'setCacheForEntity',
        );
    }
}