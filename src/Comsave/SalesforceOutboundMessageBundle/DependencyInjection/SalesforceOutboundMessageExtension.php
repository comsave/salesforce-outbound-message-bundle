<?php

namespace Comsave\SalesforceOutboundMessageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class SalesforceOutboundMessageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('comsave_salesforce_outbound_messages.wsdl_directory', $config['comsave_salesforce_outbound_messages']['wsdl_directory']);

        //DependencyInjectionBuilder::setupConfigurationParameters($container, $config, 'comsave_salesforce_outbound_messages');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../Resources/config'));
        $loader->load('services.yml');
    }
}