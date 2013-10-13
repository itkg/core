<?php

namespace Itkg\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;

class ItkgExtension extends Extension
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
        $loader->load('core.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'itkg_core';
    }
}