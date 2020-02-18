<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services;

use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\PropertyAccessor;
use Doctrine\Common\Annotations\AnnotationReader;
use LogicItLab\Salesforce\MapperBundle\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Stub\DocumentWithSalesforceFields;

/**
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater
 */
class DocumentUpdaterTest extends TestCase
{
    /**
     * @var MockObject|PropertyAccessor
     */
    private $propertyAccessorMock;

    /**
     * @var DocumentUpdater
     */
    private $documentUpdater;

    public function setUp()
    {
        $this->propertyAccessorMock = $this->createMock(PropertyAccessor::class);

        $this->documentUpdater = new DocumentUpdater($this->propertyAccessorMock);
    }

    /**
     * @covers ::updateWithValues()
     */
    public function testUpdateWithValuesSuccessful()
    {
        $product = new Product();
        $product->setName('tstPrdct');
        $product->setDescription('This is a test description');

        $newValues = [
            'id' => 'new ID',
            'description' => 'This is a new test description.',
            'name' => 'test product',
        ];

        $this->propertyAccessorMock->expects($this->atLeastOnce())
            ->method('isWritable')
            ->withConsecutive(
                [$product, 'description'],
                [$product, 'name']
            )
            ->willReturn(true);

        $this->propertyAccessorMock->expects($this->atLeastOnce())
            ->method('setValue')
            ->withConsecutive(
                [$product, 'description', 'This is a new test description.'],
                [$product, 'name', 'test product']
            );

        $this->documentUpdater->updateWithValues($product, $newValues);
    }

    /**
     * @covers ::updateWithDocument()
     */
    public function testUpdateWithDocumentSuccessful()
    {
        $product = new Product();
        $product->setName('tstPrdct');
        $product->setDescription('This is a test description');

        $newProduct = new Product();
        $newProduct->setName('test product');
        $product->setDescription('This is a new test description.');

        $this->propertyAccessorMock->expects($this->atLeastOnce())
            ->method('getValue')
            ->willReturnOnConsecutiveCalls('test product', 'This is a new test description.');

        $this->propertyAccessorMock->expects($this->atLeastOnce())
            ->method('isWritable')
            ->willReturn(true);

        $this->propertyAccessorMock->expects($this->atLeastOnce())
            ->method('isReadable')
            ->willReturn(true);

        $this->propertyAccessorMock->expects($this->atLeastOnce())
            ->method('setValue')
            ->withConsecutive(
                [$product, 'name', 'test product'],
                [$product, 'description', 'This is a new test description.']
            );

        $this->documentUpdater->updateWithDocument($product, $newProduct);
    }
}