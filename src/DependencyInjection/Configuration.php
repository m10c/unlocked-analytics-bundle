<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('m10c_unlocked_analytics');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('anonymize_ip')->defaultFalse()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
