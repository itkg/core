<?php

namespace Lemon\Mock\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * Mock extension
 * 
 * Class LemonMockExtension
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class LemonMockExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../../Resources/config')
        );

        $loader->load('mock.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias() 
    {
        return 'lemon_mock';
    }
}