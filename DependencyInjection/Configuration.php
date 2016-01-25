<?php

namespace CarnegieLearning\LdapOrmBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('carnegie_learning_ldap_orm');

        $rootNode
            ->children()
                ->arrayNode('connection')
                    ->children()
                        ->scalarNode('uri')->cannotBeEmpty()->end()
                        ->booleanNode('use_tls')->defaultFalse()->end()
                        ->scalarNode('bind_dn')->cannotBeEmpty()->end()
                        ->scalarNode('password')->cannotBeEmpty()->end()
                        ->scalarNode('password_type')->defaultValue('plaintext')->end()
                        ->booleanNode('is_active_directory')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
