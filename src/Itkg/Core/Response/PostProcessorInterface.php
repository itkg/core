<?php

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