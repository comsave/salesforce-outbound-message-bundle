<?php

namespace Comsave\SalesforceOutboundMessageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

class Configuration implements ConfigurationInterface
{
    /** @var string */
    private $symfonyVersion = Kernel::VERSION;

    public function getConfigTreeBuilder()
    {
        if(version_compare(Kernel::VERSION, '5.0') >= 0) {
            $treeBuilder = new TreeBuilder('comsave_salesforce_outbound_message');
            $rootNode = $treeBuilder->getRootNode();
        }
        else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('comsave_salesforce_outbound_message');
        }

        $rootNode
            ->children()
                ->scalarNode('wsdl_cache')->defaultValue('WSDL_CACHE_DISK')->end()
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