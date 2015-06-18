<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Resolver;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface as BaseInterface;

/**
 * ControllerResolver Interface
 */
interface ControllerResolverInterface extends BaseInterface
{
    /**
     * Set path
     *
     * @param  string $path
     *
     * @return $this
     */
    public function setPath($path);
}
