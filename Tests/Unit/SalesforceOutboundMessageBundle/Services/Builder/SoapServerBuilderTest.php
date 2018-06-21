<?php

namespace Tests\Unit\SalesforceOutboundMessageBundle\Services\Builder;

use SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder;
use SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class SoapServerBuilderTest
 * @package Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder
 */
class SoapServerBuilderTest extends TestCase
{
    /**
     * @var SoapServerBuilder
     */
    protected $soapServerBuilder;

    public function setUp()
    {
        $this->soapServerBuilder = new SoapServerBuilder();
    }

    /**
     * @covers ::build()
     */
    public function testBuildReturnsASoapServer()
    {
        $wsdlPath = '/app/comsave-webservice/src/Comsave/Webservice/Core/SalesforceOutboundMessageBundle/Services/Factory/../../Resources/wsdl/DiscountRule__c.wsdl';
        $soapRequestHandler = $this->createMock(SoapRequestHandler::class);
        $soapServer = $this->soapServerBuilder->build($wsdlPath, $soapRequestHandler);

        $this->assertInstanceOf(\SoapServer::class, $soapServer);
    }
}
