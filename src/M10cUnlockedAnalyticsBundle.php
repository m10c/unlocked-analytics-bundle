<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class M10cUnlockedAnalyticsBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
            ->booleanNode('anonymize_ip')->defaultFalse()->end()
            ->booleanNode('store_anonymous_events')->defaultFalse()->end()
            ->end()
        ;
    }

    /** @param array<string, mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter('m10c_unlocked_analytics.anonymize_ip', $config['anonymize_ip']);
        $builder->setParameter('m10c_unlocked_analytics.store_anonymous_events', $config['store_anonymous_events']);

        $container->import('../config/services.yaml');
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'M10cUnlockedAnalyticsBundle' => [
                        'type' => 'attribute',
                        'is_bundle' => false,
                        'dir' => __DIR__.'/Entity',
                        'prefix' => 'M10c\UnlockedAnalyticsBundle\Entity',
                        'alias' => 'M10cUnlockedAnalyticsBundle',
                    ],
                ],
            ],
        ]);
    }
}
