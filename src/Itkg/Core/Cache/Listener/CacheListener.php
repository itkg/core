<?php

namespace Itkg\Core\Cache\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\Cache\Event\CacheEvent;
use Itkg\Core\Cache\Event\CacheEvents;
use Itkg\Core\Event\EntityLoadEvent;
use Itkg\Core\Event\EntityLoadEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;


/**
 * Cache event listener
 */
class CacheListener implements EventSubscriberInterface
{
    /**
     * Cache adapter instance
     *
     * @var AdapterInterface
     */
    private $cache;

    /**
     * Event dispatcher instance
     *
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * Constructor
     *
     * @param AdapterInterface $cache
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(AdapterInterface $cache, EventDispatcher $dispatcher)
    {
        $this->cache = $cache;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Load data from cache
     *
     * @param EntityLoadEvent $event
     *
     * @return void
     */
    public function fetchEntityFromCache(EntityLoadEvent $event)
    {
        $entity = $event->getEntity();
        // Check cache
        if (false !== $data = $this->cache->get($entity)) {

            // Set data from cache to entity object
            $entity->setDataFromCache($data);
            $entity->setIsLoaded(true);
            $this->dispatcher->dispatch('cache.load', new CacheEvent($entity));

        }
    }

    /**
     * Set cache
     *
     * @param EntityLoadEvent $event
     *
     * @return void
     */
    public function setCacheForEntity(EntityLoadEvent $event)
    {
        $entity = $event->getEntity();

        if (!$entity->isLoaded()) {
            $this->cache->set($entity);
            $this->dispatcher->dispatch('cache.set', new CacheEvent($entity));
        }
    }

    /**
     * Remove entity cache
     *
     * @param CacheEvent $event
     *
     * @return void
     */
    public function removeEntityCache(CacheEvent $event)
    {
        $this->cache->remove($event->getCacheableData());
    }

    /**
     * Purge all entity cache
     *
     * @return void
     */
    public function purgeCache()
    {
        $this->cache->removeAll();
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
            CacheEvents::PURGE_CACHE      => 'purgeCache',
            CacheEvents::REMOVE_CACHE     => 'removeEntityCache',
            EntityLoadEvents::AFTER_LOAD  => 'setCacheForEntity',
            EntityLoadEvents::BEFORE_LOAD => 'fetchEntityFromCache',
        );
    }
}
