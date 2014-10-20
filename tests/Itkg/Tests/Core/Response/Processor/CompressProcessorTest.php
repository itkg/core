<?php

namespace Itkg\Tests\Core\Response\Processor;


use Itkg\Core\Response\Processor\CompressProcessor;
use Symfony\Component\HttpFoundation\Response;

class CompressProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $content = <<<EOF
    <html>
    <!-- A comment -->
    </html>
EOF;
        $processor = new CompressProcessor();
        $response = new Response($content);
        $processor->process($response);

        $this->assertEquals('<html></html>', $response->getContent());

    }
}