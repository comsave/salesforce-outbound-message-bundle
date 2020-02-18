<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SoapServer;

/**
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder
 */
class SoapServerBuilderTest extends TestCase
{
    /** @var SoapServerBuilder|MockObject */
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
        /** @var SoapRequestHandler|MockObject $soapRequestHandler */
        $soapRequestHandler = $this->createMock(SoapRequestHandlerInterface::class);

        $soapServer = $this->soapServerBuilder->build($wsdlPath, $soapRequestHandler);

        $this->assertInstanceOf(SoapServer::class, $soapServer);
    }
}
