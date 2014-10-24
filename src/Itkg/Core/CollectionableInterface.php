<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core;

/**
 * Interface CollectionableInterface
 * @package Itkg\Core
 */
interface CollectionableInterface
{
    /**
     * Id getter
     *
     * Needed to compute key for collections
     *
     * @return mixed
     */
    public function getId();
}
