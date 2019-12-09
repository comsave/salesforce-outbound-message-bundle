<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\DependencyInjection;

use Comsave\SalesforceOutboundMessageBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider dataTestConfiguration
     * @param array $inputConfig
     * @param array $expectedConfig
     */
    public function testConfiguration(array $inputConfig, array $expectedConfig): void
    {
        $configuration = new Configuration();

        $configNode = $configuration->getConfigTreeBuilder()->buildTree();
        $resultConfig = $configNode->finalize($configNode->normalize($inputConfig));

        $this->assertEquals($expectedConfig, $resultConfig);
    }

    public function dataTestConfiguration(): array
    {
        return [
            'comsave_salesforce_outbound_message' => [
                'wsdl_cache' => 'WSDL_CACHE_NONE',
                'wsdl_directory' => '%kernel.project_dir%/Resources/wsdl_documents',
                'document_paths' => [
                    'ObjectToBeRemoved__c' => [
                        'path' => 'Comsave\SalesforceOutboundMessageBundle\Document\ObjectToBeRemoved',
                    ],
                ],
            ],
        ];
    }
}