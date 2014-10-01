<?php

namespace Itkg\Core\Provider;

use Itkg\Core\Listener\AjaxRenderResponseListener;
use Itkg\Core\Listener\RequestMatcherListener;
use Itkg\Core\Listener\ResponseExceptionListener;
use Itkg\Core\Listener\ResponsePostRendererListener;
use Itkg\Core\Matcher\RequestMatcher;
use Itkg\Core\Response\Processor\CompressProcessor;
use Itkg\Core\Route\Router;
use Itkg\Core\View\Smarty;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProvider implements ServiceProviderInterface
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
        $container = new \Pimple();
        $container['dispatcher'] = $mainContainer->share(function ($container) {
            $dispatcher = new EventDispatcher();
            // Add listeners
            $dispatcher->addSubscriber($container['listener.request_matcher']);
            $dispatcher->addSubscriber($container['listener.response_exception']);
            $dispatcher->addSubscriber($container['listener.processor_response_render']);

            return $dispatcher;
        });

        $container['db'] = $mainContainer->share(function () {
            return \Pelican_Db::getInstance();
        });


        $container['router'] = $container->share(function() {
            return new Router();
        });

        $container['view.engine'] = $container->share(function() {
            $view = new Smarty();
            $view->setHead(new \Pelican_Index());

            return $view;
        });

        $container['request_matcher'] = $container->share(function($container) {
            return new RequestMatcher(
                $container['router']
            );
        });

        // listeners
        $container['listener.request_matcher'] = $container->share(function($container) {
            return new RequestMatcherListener(
                $container['request_matcher']
            );
        });

        $container['listener.ajax_response_render'] = $container->share(function($container) {
            return new AjaxRenderResponseListener(
                new \Pelican_Ajax_Adapter_Jquery()
            );
        });

        $container['listener.response_exception'] = $container->share(function() {
            return new ResponseExceptionListener();
        });

        $container['listener.processor_response_render'] = $container->share(function() {
            return new ResponsePostRendererListener();
        });

        // Response Processors
        $core['response.processor.compress'] = $container->share(function() {
            return new CompressProcessor();
        });

        $mainContainer['core'] = $container;
    }
}
