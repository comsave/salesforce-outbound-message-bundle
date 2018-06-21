<?php

namespace Tests\Unit\SalesforceOutboundMessageBundle\Services\Factory;

use SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class OutboundMessageWsdlPathFactoryTest
 * @package Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory
 * @coversDefaultClass \Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory
 */
class OutboundMessageWsdlPathFactoryTest extends TestCase
{
    /**
     * @var OutboundMessageWsdlPathFactory
     */
    protected $outboundMessageWsdlPathFactory;

    public function setUp()
    {
        $this->outboundMessageWsdlPathFactory = new OutboundMessageWsdlPathFactory();
    }

    /**
     * @covers ::getWsdlPath()
     */
    public function testGetWsdlPathReturnsWsdlPathOnValidObjectName()
    {
        $objectName = 'DiscountRule__c';
        $wsdlPath = $this->outboundMessageWsdlPathFactory->getWsdlPath($objectName);

        $this->assertEquals('/app/comsave-webservice/src/Comsave/Webservice/Core/SalesforceOutboundMessageBundle/Services/Factory/../../Resources/wsdl/DiscountRule__c.wsdl', $wsdlPath);
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
