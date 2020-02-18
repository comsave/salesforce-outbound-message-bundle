<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\DependencyInjection;

use Comsave\SalesforceOutboundMessageBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends TestCase
{
    /**
     * @covers ::getConfigTreeBuilder()
     */
    public function testConfiguration(): void
    {
        $inputOutput = [
            'wsdl_cache' => 'WSDL_CACHE_NONE',
            'wsdl_directory' => '%kernel.project_dir%/Resources/wsdl_documents',
            'document_paths' => [
                'ObjectToBeRemoved__c' => [
                    'path' => 'Comsave\SalesforceOutboundMessageBundle\Document\ObjectToBeRemoved',
                ],
            ],
        ];

        $configuration = new Configuration();

        $configNode = $configuration->getConfigTreeBuilder()->buildTree();
        $resultConfig = $configNode->finalize($configNode->normalize($inputOutput));

        $this->assertEquals($inputOutput, $resultConfig);
    }
}