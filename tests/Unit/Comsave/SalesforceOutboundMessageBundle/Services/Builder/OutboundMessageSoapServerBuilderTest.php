<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageDocumentClassNameFactory;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SoapServer;

/**
 * Class OutboundMessageSoapServerBuilderTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder
 */
class OutboundMessageSoapServerBuilderTest extends TestCase
{
    /**
     * @var OutboundMessageSoapServerBuilder
     */
    protected $outboundMessageSoapServerBuilder;

    /**
     * @var MockObject
     */
    private $soapServerBuilder;

    /**
     * @var MockObject
     */
    private $wsdlPathFactory;

    /**
     * @var MockObject
     */
    private $soapServerRequestHandlerBuilder;

    /**
     * @var MockObject
     */
    private $outboundMessageDocumentClassNameFactory;

    public function setUp()
    {
        $this->soapServerBuilder = $this->createMock(SoapServerBuilder::class);
        $this->wsdlPathFactory = $this->createMock(OutboundMessageWsdlPathFactory::class);
        $this->soapServerRequestHandlerBuilder = $this->createMock(SoapRequestHandlerBuilder::class);
        $this->outboundMessageDocumentClassNameFactory = $this->createMock(OutboundMessageDocumentClassNameFactory::class);
        $this->outboundMessageSoapServerBuilder = new OutboundMessageSoapServerBuilder(
            $this->soapServerBuilder,
            $this->wsdlPathFactory,
            $this->soapServerRequestHandlerBuilder,
            $this->outboundMessageDocumentClassNameFactory
        );
    }

    /**
     * @covers ::build()
     */
    public function testBuildReturnsASoapServer()
    {
        $this->wsdlPathFactory->expects($this->once())
            ->method('getWsdlPath')
            ->willReturn('path/to/document.wsdl');

        $soapRequestHandler = $this->createMock(SoapRequestHandler::class);

        $this->soapServerRequestHandlerBuilder->expects($this->once())
            ->method('build')
            ->willReturn($soapRequestHandler);

        $soapServerMock = $this->createMock(SoapServer::class);

        $this->soapServerBuilder->expects($this->once())
            ->method('build')
            ->willReturn($soapServerMock);

        $objectName = 'Product';

        $this->outboundMessageDocumentClassNameFactory->expects($this->once())
            ->method('getClassName')
            ->with($objectName)
            ->willReturn('DocumentClassPathName');

        $soapServer = $this->outboundMessageSoapServerBuilder->build($objectName);

        $this->assertEquals($soapServer, $soapServerMock);
    }
}
