<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class SoapServerBuilderTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder
 */
class SoapServerBuilderTest extends TestCase
{
    /**
     * @var SoapServerBuilder
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
        $soapRequestHandler = $this->createMock(SoapRequestHandler::class);

        $soapServer = $this->soapServerBuilder->build($wsdlPath, $soapRequestHandler);

        $this->assertInstanceOf(\SoapServer::class, $soapServer);
    }
}
