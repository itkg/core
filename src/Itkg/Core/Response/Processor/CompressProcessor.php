<?php

namespace Itkg\Core\Response\Processor;

use Itkg\Core\Response\PostProcessorInterface;
use Symfony\Component\HttpFoundation\Response;

class CompressProcessor implements PostProcessorInterface
{

    /**
     * Drop comments & compress response content
     *
     * @param Response $response
     */
    public function process(Response $response)
    {
        $content = preg_replace('/<!\-\-.[^(\-\-\>)]*?\-\->/i', '', $response->getContent());

        $content = str_replace("\t", " ", $content);
        $content = preg_replace('/(\s)\/\/(.*)(\s)/', '\\1/* \\2 */\\3', $content);
        $search = array(
            '/(\s+)?(\<.+\>)(\s+)?/',
            '/(\s)+/s'
        );
        $replace = array(
            '\\2',
            '\\1'
        );
        $content = preg_replace($search, $replace, $content);
        $content = str_replace("\n", "", $content);

        $response->setContent($content);

    }
}