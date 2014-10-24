<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface PostProcessorInterface
 *
 * Describe respone post processing
 *
 * @package Itkg\Core\Response
 */
interface PostProcessorInterface
{
    /**
     * @param Response $response
     */
    public function process(Response $response);
}
