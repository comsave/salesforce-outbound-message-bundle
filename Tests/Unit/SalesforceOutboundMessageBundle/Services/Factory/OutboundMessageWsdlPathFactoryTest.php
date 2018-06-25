<?php

namespace Tests\Unit\SalesforceOutboundMessageBundle\Services\Factory;

use SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class OutboundMessageWsdlPathFactoryTest
 * @package Tests\Unit\SalesforceOutboundMessageBundle\Services\Factory
 * @coversDefaultClass \SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory
 */
class OutboundMessageWsdlPathFactoryTest extends TestCase
{
    /**
     * @var OutboundMessageWsdlPathFactory
     */
    protected $outboundMessageWsdlPathFactory;

    public function setUp()
    {
        $this->outboundMessageWsdlPathFactory = new OutboundMessageWsdlPathFactory('Tests/Resources/wsdl/');
    }

    /**
     * @covers ::getWsdlPath()
     */
    public function testGetWsdlPathReturnsWsdlPathOnValidObjectName()
    {
        $objectName = 'DiscountRule__c';
        $wsdlPath = $this->outboundMessageWsdlPathFactory->getWsdlPath($objectName);

        $this->assertEquals('Tests/Resources/wsdl/DiscountRule__c.wsdl', $wsdlPath);
    }

    /**
     * @covers ::getWsdlPath()
     * @expectedException \SalesforceOutboundMessageBundle\Exception\SalesforceException
     */
    public function testGetWsdlPathThrowsExceptionWhenFileCantBeFound()
    {
        $objectName = 'DoesNotExist';
        $this->outboundMessageWsdlPathFactory->getWsdlPath($objectName);
    }
}
