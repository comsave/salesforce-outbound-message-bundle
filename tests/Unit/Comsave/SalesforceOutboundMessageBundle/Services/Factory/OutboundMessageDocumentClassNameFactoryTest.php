<?php

namespace SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\SalesforceOutboundMessageBundle\Exception\DocumentNotFoundException;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\SalesforceObjectDocumentMetadataFactory;
use PHPUnit\Framework\TestCase;

class SalesforceObjectDocumentMetadataFactoryTest extends TestCase
{
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

    public function testIsComparableTrue(): void
    {
        $documentLocations = [
            'Subscriber' => [
                'force_compare' => true
            ]
        ];

        $this->assertTrue((new SalesforceObjectDocumentMetadataFactory($documentLocations))->isForceCompared('Subscriber'));
    }

    public function testIsComparableFalse(): void
    {
        $documentLocations = [
            'Subscriber' => [
                'force_compare' => false
            ]
        ];

        $this->assertFalse((new SalesforceObjectDocumentMetadataFactory($documentLocations))->isForceCompared('Subscriber'));
    }

    public function testIsComparableFalseIfNotSet(): void
    {
        $documentLocations = [
            'Subscriber' => [
            ]
        ];

        $this->assertFalse((new SalesforceObjectDocumentMetadataFactory($documentLocations))->isForceCompared('Subscriber'));
    }
}
