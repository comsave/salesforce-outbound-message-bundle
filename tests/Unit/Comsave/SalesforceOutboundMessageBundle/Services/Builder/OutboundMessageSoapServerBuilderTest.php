<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\SalesforceObjectDocumentMetadataFactory;
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
     * @var MockObject|SoapServerBuilder
     */
    private $soapServerBuilderMock;

    /**
     * @var MockObject|OutboundMessageWsdlPathFactory
     */
    private $wsdlPathFactoryMock;

    /**
     * @var MockObject|SoapRequestHandlerBuilder
     */
    private $soapRequestHandlerBuilderMock;

    /**
     * @var MockObject
     */
    private $salesforceObjectMetadataFactoryMock;

    public function setUp()
    {
        $this->soapServerBuilderMock = $this->createMock(SoapServerBuilder::class);
        $this->wsdlPathFactoryMock = $this->createMock(OutboundMessageWsdlPathFactory::class);
        $this->soapRequestHandlerBuilderMock = $this->createMock(SoapRequestHandlerBuilder::class);
        $this->salesforceObjectMetadataFactoryMock = $this->createMock(SalesforceObjectDocumentMetadataFactory::class);
        $this->outboundMessageSoapServerBuilder = new OutboundMessageSoapServerBuilder(
            $this->soapServerBuilderMock,
            $this->wsdlPathFactoryMock,
            $this->soapRequestHandlerBuilderMock,
            $this->salesforceObjectMetadataFactoryMock
        );
    }

    /**
     * @covers ::build()
     */
    public function testBuildReturnsASoapServer()
    {
        $this->wsdlPathFactoryMock->expects($this->once())
            ->method('getWsdlPath')
            ->willReturn('path/to/document.wsdl');

        $soapRequestHandler = $this->createMock(SoapRequestHandler::class);

        $this->soapRequestHandlerBuilderMock->expects($this->once())
            ->method('build')
            ->willReturn($soapRequestHandler);

        $soapServerMock = $this->createMock(SoapServer::class);

        $this->soapServerBuilderMock->expects($this->once())
            ->method('build')
            ->willReturn($soapServerMock);

        $objectName = 'Product';

        $this->salesforceObjectMetadataFactoryMock->expects($this->once())
            ->method('getClassName')
            ->with($objectName)
            ->willReturn('DocumentClassPathName');

        $soapServer = $this->outboundMessageSoapServerBuilder->build($objectName);

        $this->assertEquals($soapServer, $soapServerMock);
    }
}
