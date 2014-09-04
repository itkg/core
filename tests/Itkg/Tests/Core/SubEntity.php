<?php

namespace Itkg\Tests\Core;

use Itkg\Tests\Core\Entity as BaseEntity;
use Itkg\Core\CachableInterface;

/**
 * Fake entity model for testing purpose
 */
class SubEntity extends BaseEntity implements CachableInterface
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