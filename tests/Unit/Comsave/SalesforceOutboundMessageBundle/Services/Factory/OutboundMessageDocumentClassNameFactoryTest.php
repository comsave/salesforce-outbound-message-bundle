<?php

namespace SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\SalesforceOutboundMessageBundle\Exception\DocumentNotFoundException;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\SalesforceObjectDocumentMetadataFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass SalesforceObjectDocumentMetadataFactory
 */
class SalesforceObjectDocumentMetadataFactoryTest extends TestCase
{
    /**
     * @covers ::getClassName()
     */
    public function testGetClassNameSucceeds(): void
    {
        $documentLocations = [
            'Account' => [
                'path' => 'App\Comsave\Document\Account',
            ]
        ];

        $this->assertEquals(
            'App\Comsave\Document\Account',
            (new SalesforceObjectDocumentMetadataFactory($documentLocations))->getClassName('Account')
        );
    }

    /**
     * @covers ::getClassName()
     */
    public function testGetClassNameThrowsExceptionIfNotFound(): void
    {
        $documentLocations = [
            'Account2' => [
                'path' => 'App\Comsave\Document\Account2',
            ]
        ];

        $this->expectException(DocumentNotFoundException::class);

        (new SalesforceObjectDocumentMetadataFactory($documentLocations))->getClassName('Account');
    }

    /**
     * @covers ::isForceCompared()
     */
    public function testIsComparableTrue(): void
    {
        $documentLocations = [
            'Subscriber' => [
                'force_compare' => true
            ]
        ];

        $this->assertTrue((new SalesforceObjectDocumentMetadataFactory($documentLocations))->isForceCompared('Subscriber'));
    }

    /**
     * @covers ::isForceCompared()
     */
    public function testIsComparableFalse(): void
    {
        $documentLocations = [
            'Subscriber' => [
                'force_compare' => false
            ]
        ];

        $this->assertFalse((new SalesforceObjectDocumentMetadataFactory($documentLocations))->isForceCompared('Subscriber'));
    }

    /**
     * @covers ::isForceCompared()
     */
    public function testIsComparableFalseIfNotSet(): void
    {
        $documentLocations = [
            'Subscriber' => [
            ]
        ];

        $this->assertFalse((new SalesforceObjectDocumentMetadataFactory($documentLocations))->isForceCompared('Subscriber'));
    }
}
