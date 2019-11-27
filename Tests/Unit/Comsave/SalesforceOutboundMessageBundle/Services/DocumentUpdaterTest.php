<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services;

use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\PropertyAccessor;
use Doctrine\Common\Annotations\AnnotationReader;
use LogicItLab\Salesforce\MapperBundle\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class DocumentUpdaterTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater
 */
class DocumentUpdaterTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $propertyAccessor;

    /**
     * @var MockObject
     */
    private $annotationReader;

    /**
     * @var DocumentUpdater
     */
    private $documentUpdater;

    public function setUp()
    {
        $this->propertyAccessor = $this->createMock(PropertyAccessor::class);
        $this->annotationReader = $this->createMock(AnnotationReader::class);

        $this->documentUpdater = new DocumentUpdater($this->propertyAccessor, $this->annotationReader);
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

        $this->propertyAccessor->expects($this->atLeastOnce())
            ->method('isWritable')
            ->withConsecutive(
                [$product, 'description'],
                [$product, 'name']
            )
            ->willReturn(true);

        $this->propertyAccessor->expects($this->atLeastOnce())
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

        $this->propertyAccessor->expects($this->atLeastOnce())
            ->method('getValue')
            ->willReturnOnConsecutiveCalls('test product', 'This is a new test description.');

        $this->propertyAccessor->expects($this->atLeastOnce())
            ->method('isWritable')
            ->willReturn(true);

        $this->propertyAccessor->expects($this->atLeastOnce())
            ->method('isReadable')
            ->willReturn(true);

        $this->propertyAccessor->expects($this->atLeastOnce())
            ->method('setValue')
            ->withConsecutive(
                [$product, 'name', 'test product'],
                [$product, 'description', 'This is a new test description.']
            );

        $this->documentUpdater->updateWithDocument($product, $newProduct);
    }
}