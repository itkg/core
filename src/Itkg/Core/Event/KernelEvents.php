<?php

namespace Itkg\Core\Event;

use Itkg\Core\ServiceContainer;

/**
 * Class KernelEvents
 *
 * @package Itkg\Core\Event
 */
final class KernelEvents
{
    const CONFIG_LOADED = 'kernel.config.loaded';
    const APP_LOADED = 'kernel.app.loaded';
}
