<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Event;

/**
 * Entity load events
 */
final class EntityLoadEvents
{
    const BEFORE_LOAD = 'entity.before.load';
    const AFTER_LOAD  = 'entity.after.load';
}
