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

use Itkg\Core\Listener\RequestMatcherListener;
use Itkg\Core\Matcher\RequestMatcher;
use Itkg\Core\Route\Router;

/**
 * Manage router services
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceRouterProvider implements ServiceProviderInterface
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
        $mainContainer['router'] = $mainContainer->share(function () {
            return new Router();
        });

        $mainContainer['request_matcher'] = $mainContainer->share(function ($mainContainer) {
            return new RequestMatcher(
                $mainContainer['router']
            );
        });

        // listeners
        $mainContainer['listener.request_matcher'] = $mainContainer->share(function ($mainContainer) {
            return new RequestMatcherListener(
                $mainContainer['request_matcher']
            );
        });

        $mainContainer['dispatcher']->addSubscriber($mainContainer['listener.request_matcher']);
    }
}
