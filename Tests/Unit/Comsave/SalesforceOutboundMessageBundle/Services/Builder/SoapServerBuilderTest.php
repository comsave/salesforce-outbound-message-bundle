<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class SoapServerBuilderTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder
 */
class SoapServerBuilderTest extends TestCase
{
    /**
     * @var SoapServerBuilder|MockObject
     */
    protected $soapServerBuilder;

    public function setUp()
    {
        $wsdlCache = 'WSDL_CACHE_DISK';

        $this->soapServerBuilder = new SoapServerBuilder($wsdlCache);
    }

    /**
     * @covers ::build()
     */
    public function testBuildReturnsASoapServer()
    {
        $wsdlPath = 'Tests/Resources/wsdl/DiscountRule__c.wsdl';
        $soapRequestHandler = $this->createMock(SoapRequestHandlerInterface::class);

        $soapServer = $this->soapServerBuilder->build($wsdlPath, $soapRequestHandler);

        $this->assertInstanceOf(\SoapServer::class, $soapServer);
    }
}
