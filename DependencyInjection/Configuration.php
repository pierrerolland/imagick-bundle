<?php

namespace Rolland\ImagickBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rolland_imagick');

        $rootNode
            ->children()
                ->scalarNode('cache_dir')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('web_dir')
                    ->defaultValue('%kernel.root_dir%/../web')
                ->end()
                ->arrayNode('filters')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->useAttributeAsKey('operationName')
                        ->prototype('variable')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
