<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\Provider;

use Itkg\Core\Command\DatabaseList\Finder;
use Itkg\Core\Command\DatabaseListCommand;
use Itkg\Core\Command\DatabaseUpdate\Loader;
use Itkg\Core\Command\DatabaseUpdate\Locator;
use Itkg\Core\Command\DatabaseUpdate\Migration\Factory;
use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;
use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Runner;
use Itkg\Core\Command\DatabaseUpdate\Setup;
use Itkg\Core\Command\DatabaseUpdate\Template\Loader as TemplateLoader;
use Itkg\Core\Command\DatabaseUpdate;
use Itkg\Core\Command\DatabaseUpdateCommand;
use Itkg\Core\Provider\ServiceProviderInterface;

/**
 * Class ServiceCommandProvider
 *
 * A provider for db_update command injection
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
        $container['itkg-core.command.db_update.runner'] = $container->share(
            function ($container) {
                return new Runner($container['doctrine.connection']);
            }
        );

        $container['itkg-core.command.db_update.decorator'] = $container->share(
            function () {
                return new DatabaseUpdate\Query\Decorator(
                    new TemplateLoader()
                );
            }
        );

        $container['itkg-core.command.db_update.display'] = $container->share(
            function () {
                return new DatabaseUpdate\Display(
                    new DatabaseUpdate\Layout\Parser(),
                    new Formatter()
                );
            }
        );

        $container['itkg-core.command.db_update.setup'] = $container->share(
            function ($container) {
                return new Setup(
                    $container['itkg-core.command.db_update.runner'],
                    new Loader($container['doctrine.connection']),
                    new Factory(),
                    new Locator(),
                    $container['itkg-core.command.db_update.decorator'],
                    new DatabaseUpdate\ReleaseChecker()
                );
            }
        );

        $container['itkg-core.command.database_update'] = $container->share(
            function ($container) {
                return new DatabaseUpdateCommand(
                    $container['itkg-core.command.db_update.setup'],
                    $container['itkg-core.command.db_update.display'],
                    'itkg-core:database:update'
                );
            }
        );

        $container['itkg-core.command.database_list'] = $container->share(
            function () {
                return new DatabaseListCommand(
                    new Locator(),
                    new Finder(),
                    new DatabaseUpdate\ReleaseChecker(),
                    'itkg-core:database:list'
                );
            }
        );
    }
}
