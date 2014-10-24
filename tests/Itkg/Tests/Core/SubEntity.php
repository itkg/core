<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core;

use Itkg\Tests\Core\Entity as BaseEntity;
use Itkg\Core\CacheableInterface;

/**
 * Fake entity model for testing purpose
 */
class SubEntity extends BaseEntity implements CacheableInterface
{
    /**
     * Property prefix used in arrayAccess & database columns
     */
    const PROPERTY_PREFIX = 'SUB';

    public function __construct()
    {
        // do nothing to avoid build of sub object and infinite loop
    }

    public function getHashKey()
    {
        return 'my_hash_key';
    }
}