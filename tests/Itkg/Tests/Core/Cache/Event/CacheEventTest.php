<?php

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class CacheEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $key = 'my.cache.key';
        $value = 'my value';
        $size = strlen($value);
        $ttl = 86400;
        $event = new \Itkg\Core\Cache\Event\CacheEvent($key, $ttl, $value);

        $this->assertEquals($key, $event->getKey());
        $this->assertEquals($size, $event->getSize());
        $this->assertEquals($ttl, $event->getTtl());
        $this->assertEquals($value, $event->getValue());
    }
} 