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

use Itkg\Core\Listener\AjaxRenderResponseListener;

/**
 * Manage old dependencies services
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceLegacyProvider implements ServiceProviderInterface
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
        $mainContainer['db'] = $mainContainer->share(function () {
            return \Pelican_Db::getInstance();
        });




        $mainContainer['listener.ajax_response_render'] = $mainContainer->share(function () {
            return new AjaxRenderResponseListener(
                new \Pelican_Ajax_Adapter_Jquery()
            );
        });
    }

} 