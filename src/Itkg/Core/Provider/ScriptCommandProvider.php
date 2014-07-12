<?php

namespace Itkg\Core\Provider;

use Itkg\Core\Command\Script\Loader;
use Itkg\Core\Command\Script\Migration\Factory;
use Itkg\Core\Command\Script\Runner;
use Itkg\Core\Command\Script\Setup;
use Itkg\Core\Command\ScriptCommand;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ScriptCommandProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple $container An Container instance
     */
    public function register(\Pimple $container)
    {
        $container['itkg-core.command.script.runner'] = $container->share(
            function ($container) {
                return new Runner(
                    $container['doctrine.connection']
                );
            }
        );

        $container['itkg-core.command.script.loader'] = $container->share(
            function ($container) {
                return new Loader(
                    $container['doctrine.connection']
                );
            }
        );

        $container['itkg-core.command.script.migration_factory'] = $container->share(
            function () {
                return new Factory();
            }
        );

        $container['itkg-core.command.script.setup'] = $container->share(
            function ($container) {
                return new Setup(
                    $container['itkg-core.command.script.runner'],
                    $container['itkg-core.command.script.loader'],
                    $container['itkg-core.command.script.migration_factory']
                );
            }
        );

        $container['itkg-core.command.script'] = $container->share(
            function ($container) {
                return new ScriptCommand(
                    'itkg-core:script',
                    $container['itkg-core.command.script.setup']
                );
            }
        );
    }
}
