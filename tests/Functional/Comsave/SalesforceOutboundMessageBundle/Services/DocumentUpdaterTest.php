<?php

namespace Tests\Functional\Comsave\SalesforceOutboundMessageBundle\Services;

use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\PropertyAccessor;
use Doctrine\Common\Collections\ArrayCollection;
use LogicItLab\Salesforce\MapperBundle\Annotation\AnnotationReader;
use LogicItLab\Salesforce\MapperBundle\Annotation\Field;
use PHPUnit\Framework\TestCase;
use Tests\Stub\DocumentWithSalesforceFields;

/**
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater
 */
class DocumentUpdaterTest extends TestCase
{
    /** @var DocumentUpdater */
    private $documentUpdater;

    /** @var AnnotationReader */
    private $salesforceAnnotationReader;

    public function setUp(): void
    {
        $this->documentUpdater = new DocumentUpdater(
            new PropertyAccessor()
        );

        $this->salesforceAnnotationReader = new AnnotationReader(new \Doctrine\Common\Annotations\AnnotationReader());
    }

    public function getAllowedProperties(string $documentClass): array
    {
        /** @var Field[]|ArrayCollection|null $salesforceFields */
        $salesforceFields = $this->salesforceAnnotationReader->getSalesforceFields($documentClass);

        if(!$salesforceFields instanceof ArrayCollection) {
            return [];
        }

        return array_keys($salesforceFields->toArray());
    }

    /**
     * @covers ::updateWithDocument()
     */
    public function testOnlySetsAllowedProperties(): void
    {
        $existingDocument = new DocumentWithSalesforceFields();
        $existingDocument->setSomeField('111');
        $existingDocument->setSomeUnmappedField('222');
        $existingDocument->setSomeOtherField('333');

        $newDocument = new DocumentWithSalesforceFields();
        $newDocument->setSomeField('111x');
        $newDocument->setSomeUnmappedField('222x');
        $newDocument->setSomeOtherField('333x');

        $this->documentUpdater->updateWithDocument($existingDocument, $newDocument, [
            'someField',
            'someOtherField'
        ]);

        $this->assertEquals('111x', $existingDocument->getSomeField());
        $this->assertEquals('222', $existingDocument->getSomeUnmappedField());
        $this->assertEquals('333x', $existingDocument->getSomeOtherField());
    }

    /**
     * @covers ::updateWithDocument()
     */
    public function testDoesNotSetIgnoredProperties(): void
    {
        $existingDocument = new DocumentWithSalesforceFields();
        $existingDocument->setSomeField('111');
        $existingDocument->setSomeUnmappedField('222');
        $existingDocument->setSomeOtherField('333');

        $newDocument = new DocumentWithSalesforceFields();
        $newDocument->setSomeField('111x');
        $newDocument->setSomeUnmappedField('222x');
        $newDocument->setSomeOtherField('333x');

        $this->documentUpdater->updateWithDocument($existingDocument, $newDocument, null, [
            'someField',
        ]);

        $this->assertEquals('111', $existingDocument->getSomeField());
        $this->assertEquals('222x', $existingDocument->getSomeUnmappedField());
        $this->assertEquals('333x', $existingDocument->getSomeOtherField());
    }

    /**
     * @covers ::updateWithDocument()
     */
    public function testDoesNotSetUnmappedProperties(): void
    {
        $existingDocument = new DocumentWithSalesforceFields();
        $existingDocument->setSomeField('111');
        $existingDocument->setSomeUnmappedField('222');
        $existingDocument->setSomeOtherField('333');

        $newDocument = new DocumentWithSalesforceFields();
        $newDocument->setSomeField('111x');
        $newDocument->setSomeUnmappedField('222x');
        $newDocument->setSomeOtherField('333x');

        $this->documentUpdater->updateWithDocument($existingDocument, $newDocument, $this->getAllowedProperties(DocumentWithSalesforceFields::class));

        $this->assertEquals('111x', $existingDocument->getSomeField());
        $this->assertEquals('222', $existingDocument->getSomeUnmappedField());
        $this->assertEquals('333x', $existingDocument->getSomeOtherField());
    }
}