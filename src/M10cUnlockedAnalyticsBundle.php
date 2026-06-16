<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle;

use M10c\UnlockedAnalyticsBundle\Storage\ClickHouseEventStore;
use M10c\UnlockedAnalyticsBundle\Storage\DoctrineEventStore;
use M10c\UnlockedAnalyticsBundle\Storage\EventStoreInterface;
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
            ->enumNode('storage')
                ->info('Where events are stored: "doctrine" or "clickhouse".')
                ->values(['doctrine', 'clickhouse'])
                ->defaultValue('doctrine')
            ->end()
            ->arrayNode('clickhouse')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('dsn')
                        ->info('ClickHouse DSN. Required for clickhouse storage.')
                        ->defaultNull()
                    ->end()
                ->end()
            ->end()
            ->end()
        ;
    }

    /** @param array<string, mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter('m10c_unlocked_analytics.anonymize_ip', $config['anonymize_ip']);
        $builder->setParameter('m10c_unlocked_analytics.store_anonymous_events', $config['store_anonymous_events']);

        $storage = $config['storage'];
        $dsn = $config['clickhouse']['dsn'] ?? null;
        if ('clickhouse' === $storage && (null === $dsn || '' === $dsn)) {
            throw new \InvalidArgumentException('m10c_unlocked_analytics.clickhouse.dsn must be set when storage is "clickhouse".');
        }
        $builder->setParameter('m10c_unlocked_analytics.clickhouse_dsn', (string) $dsn);

        $container->import('../config/services.yaml');

        $container->services()->alias(
            EventStoreInterface::class,
            'clickhouse' === $storage ? ClickHouseEventStore::class : DoctrineEventStore::class,
        );
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if ('clickhouse' === $this->resolveStorage($builder)) {
            return;
        }

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

    private function resolveStorage(ContainerBuilder $builder): string
    {
        $storage = 'doctrine';
        foreach ($builder->getExtensionConfig('m10c_unlocked_analytics') as $cfg) {
            if (isset($cfg['storage']) && \is_string($cfg['storage'])) {
                $storage = $cfg['storage'];
            }
        }

        return $storage;
    }
}
