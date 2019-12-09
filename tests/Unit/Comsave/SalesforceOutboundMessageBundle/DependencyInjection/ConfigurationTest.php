<?php

namespace Tests\Unit;

use Comsave\SalesforceOutboundMessageBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider dataTestConfiguration
     *
     * @param mixed $inputConfig
     * @param mixed $expectedConfig
     */
    public function testConfiguration($inputConfig, $expectedConfig)
    {
        $configuration = new Configuration();

        $node = $configuration->getConfigTreeBuilder()
            ->buildTree();
        $normalizedConfig = $node->normalize($inputConfig);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    public function dataTestConfiguration()
    {
        return [
            'test configuration'   => [
                ['input'],
                ['expected_config']
            ],
            // ...
        ];
    }
}