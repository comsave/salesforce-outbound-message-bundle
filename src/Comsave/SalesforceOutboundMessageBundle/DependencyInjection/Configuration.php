<?php

namespace Comsave\SalesforceOutboundMessageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('comsave_salesforce_outbound_message');
        $rootNode
            ->children()
            ->scalarNode('wsdl_directory')->isRequired()->end()
            ->arrayNode('document_paths')
                ->useAttributeAsKey('name', false)
                ->prototype('array')
                ->append($this->getDocumentPath())
                ->end()
            ->end();
        return $treeBuilder;
    }

    private function getDocumentPath()
    {
        return new ScalarNodeDefinition('path');
    }

    private function getDocumentName()
    {
        return new ScalarNodeDefinition('name');
    }
}