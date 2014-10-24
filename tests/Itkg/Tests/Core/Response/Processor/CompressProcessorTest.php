<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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