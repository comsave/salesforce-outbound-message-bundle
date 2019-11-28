<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services;

use Comsave\SalesforceOutboundMessageBundle\Services\PropertyAccessor;
use LogicItLab\Salesforce\MapperBundle\Model\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class PropertyAccessorTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\PropertyAccessor
 */
class PropertyAccessorTest extends TestCase
{
    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    public function setUp()
    {
        $this->propertyAccessor = new PropertyAccessor();
    }

    /**
     * @covers ::setValue()
     * @covers ::getPropertySetter()
     */
    public function testSetValueSuccess()
    {
        $product = new Product();

        $this->propertyAccessor->setValue($product, 'name', 'product name');

        $this->assertEquals('product name', $product->getName());
    }

    /**
     * @covers ::getValue()
     * @covers ::getPropertyGetter()
     */
    public function testGetValueSuccess()
    {
        $product = new Product();
        $product->setName('Product');

        $productMame = $this->propertyAccessor->getValue($product, 'name');

        $this->assertEquals('Product', $productMame);
    }

    /**
     * @covers ::isReadable()
     * @covers ::getPropertyGetter()
     */
    public function testIsReadableReturnsTrueOnExistingField()
    {
        $product = new Product();

        $this->assertTrue($this->propertyAccessor->isReadable($product, 'name'));
    }

    /**
     * @covers ::isReadable()
     * @covers ::getPropertyGetter()
     */
    public function testIsReadableReturnsFalseOnNonExistingField()
    {
        $product = new Product();

        $this->assertFalse($this->propertyAccessor->isReadable($product, 'thisFieldDoesNotExist'));
    }

    /**
     * @covers ::isWritable()
     * @covers ::getPropertySetter()
     */
    public function testIsWritableReturnsTrueOnExistingField()
    {
        $product = new Product();

        $this->assertTrue($this->propertyAccessor->isWritable($product, 'name'));
    }

    /**
     * @covers ::isWritable()
     * @covers ::getPropertySetter()
     */
    public function testIsWritableReturnsFalseOnNonExistingField()
    {
        $product = new Product();

        $this->assertFalse($this->propertyAccessor->isWritable($product, 'thisFieldDoesNotExist'));
    }
}