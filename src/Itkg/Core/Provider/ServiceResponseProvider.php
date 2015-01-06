<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Provider;

use Itkg\Core\Listener\ResponseExceptionListener;
use Itkg\Core\Listener\ResponsePostRendererListener;
use Itkg\Core\Response\Processor\CompressProcessor;

/**
 * Manage response services (listeners, processors, etc)
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProcessorProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple $mainContainer An Container instance
     */
    public function register(\Pimple $mainContainer)
    {
        $mainContainer['listener.response_exception'] = $mainContainer->share(function () {
            return new ResponseExceptionListener();
        });

        $mainContainer['listener.processor_response_render'] = $mainContainer->share(function () {
            return new ResponsePostRendererListener();
        });

        // Response Processors
        $mainContainer['response.processor.compress'] = $mainContainer->share(function () {
            return new CompressProcessor();
        });

        $dispatcher = $mainContainer['dispatcher'];
        
        $dispatcher->addSubscriber($mainContainer['listener.response_exception']);
        $dispatcher->addSubscriber($mainContainer['listener.processor_response_render']);
    }
}
