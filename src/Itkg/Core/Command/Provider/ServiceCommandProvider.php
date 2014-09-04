<?php

namespace Itkg\Core\Command\Provider;

use Itkg\Core\Command\DatabaseUpdate\Loader;
use Itkg\Core\Command\DatabaseUpdate\Locator;
use Itkg\Core\Command\DatabaseUpdate\Migration\Factory;
use Itkg\Core\Command\DatabaseUpdate\Query\OutputColorQueryDisplay;
use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryDisplay;
use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Query\QueryFormatter;
use Itkg\Core\Command\DatabaseUpdate\Runner;
use Itkg\Core\Command\DatabaseUpdate\Setup;
use Itkg\Core\Command\DatabaseUpdate;
use Itkg\Core\Command\DatabaseUpdateCommand;
use Itkg\Core\Provider\ServiceProviderInterface;

/**
 * Class ServiceCommandProvider
 *
 * A provider for database_update command injection
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceCommandProvider implements ServiceProviderInterface
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
        $container['itkg-core.command.database_update.runner'] = $container->share(function ($container) {
            return new Runner(
                $container['doctrine.connection']
            );
        });

        $container['itkg-core.command.database_update.loader'] = $container->share(function ($container) {
            return new Loader(
                $container['doctrine.connection']
            );
        });

        $container['itkg-core.command.database_update.query_formatter'] = $container->share(function () {
            return new QueryFormatter();
        });

        $container['itkg-core.command.database_update.output_query_display'] = $container->share(function ($container) {
            return new OutputQueryDisplay(
                $container['itkg-core.command.database_update.query_formatter']
            );
        });

        $container['itkg-core.command.database_update.output_color_query_display'] = $container->share(function ($container) {
            return new OutputColorQueryDisplay(
                $container['itkg-core.command.database_update.query_formatter']
            );
        });

        $container['itkg-core.command.database_update.migration_factory'] = $container->share(function () {
            return new Factory();
        });

        $container['itkg-core.command.database_update.output_query_factory'] = $container->share(function ($container) {
            return new OutputQueryFactory(
                $container['itkg-core.command.database_update.query_formatter']
            );
        });

        $container['itkg-core.command.database_update.setup'] = $container->share(function ($container) {
                return new Setup(
                    $container['itkg-core.command.database_update.runner'],
                    $container['itkg-core.command.database_update.loader'],
                    $container['itkg-core.command.database_update.migration_factory'],
                    $container['itkg-core.command.database_update.locator']
                );
            }
        );

        $container['itkg-core.command.database_update.locator'] = $container->share(function () {
            return new Locator();
        });
        $container['itkg-core.command.database_update'] = $container->share(function ($container) {
            return new DatabaseUpdateCommand(
                'itkg-core:database:update',
                $container['itkg-core.command.database_update.setup'],
                $container['itkg-core.command.database_update.output_query_factory']
            );
        });
    }
}
