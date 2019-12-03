<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class OutboundMessageWsdlPathFactoryTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Factory
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory
 */
class OutboundMessageWsdlPathFactoryTest extends TestCase
{
    /**
     * @var OutboundMessageWsdlPathFactory
     */
    protected $outboundMessageWsdlPathFactory;

    public function setUp()
    {
        $this->outboundMessageWsdlPathFactory = new OutboundMessageWsdlPathFactory('tests/Resources/wsdl/');
    }

    /**
     * @covers ::getWsdlPath()
     */
    public function testGetWsdlPathReturnsWsdlPathOnValidObjectName()
    {
        $objectName = 'DiscountRule__c';
        $wsdlPath = $this->outboundMessageWsdlPathFactory->getWsdlPath($objectName);

        $this->assertEquals('tests/Resources/wsdl/DiscountRule__c.wsdl', $wsdlPath);
    }

    /**
     * @covers ::getWsdlPath()
     * @expectedException \Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException
     */
    public function testGetWsdlPathThrowsExceptionWhenFileCantBeFound()
    {
        $objectName = 'DoesNotExist';
        $this->outboundMessageWsdlPathFactory->getWsdlPath($objectName);
    }
}