<?php

namespace Itkg\Core\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SubscriberCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('event_dispatcher')) {
            return;
        }

        $definition = $container->getDefinition(
            'event_dispatcher'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'core.subscriber'
        );
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addSubscriber',
                array(new Reference($id))
            );
        }
    }
}