<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapResponseBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\OutboundMessageRequestHandler;
use Comsave\SalesforceOutboundMessageBundle\Services\Resolver\OutboundMessageObjectNameResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SoapServer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\OutboundMessageRequestHandler
 */
class OutboundMessageRequestHandlerTest extends TestCase
{
    /**
     * @var OutboundMessageRequestHandler
     */
    protected $outboundMessageRequestHandler;

    /**
     * @var OutboundMessageSoapServerBuilder|MockObject
     */
    private $outboundMessageSoapServerBuilderMock;

    /**
     * @var SoapResponseBuilder|MockObject
     */
    private $soapServerResponseBuilderMock;

    /**
     * @var OutboundMessageObjectNameResolver|MockObject
     */
    private $outboundMessageObjectNameResolverMock;

    public function setUp()
    {
        $this->outboundMessageSoapServerBuilderMock = $this->createMock(OutboundMessageSoapServerBuilder::class);
        $this->soapServerResponseBuilderMock = $this->createMock(SoapResponseBuilder::class);
        $this->outboundMessageObjectNameResolverMock = $this->createMock(OutboundMessageObjectNameResolver::class);

        $this->outboundMessageRequestHandler = new OutboundMessageRequestHandler(
            $this->outboundMessageSoapServerBuilderMock,
            $this->soapServerResponseBuilderMock,
            $this->outboundMessageObjectNameResolverMock
        );
    }

    /**
     * @covers ::handle()
     */
    public function testHandleSuccess()
    {
        $documentName = 'document/name/folder/file';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';

        $this->outboundMessageObjectNameResolverMock->expects($this->once())
            ->method('resolve')
            ->willReturn('objectName');

        $soapServerMock = $this->createMock(SoapServer::class);
        $soapServerMock->expects($this->once())
            ->method('handle')
            ->with($xml);

        $this->outboundMessageSoapServerBuilderMock->expects($this->once())
            ->method('build')
            ->with('objectName')
            ->willReturn($soapServerMock);

        $response = $this->createMock(Response::class);

        $this->soapServerResponseBuilderMock->expects($this->once())
            ->method('build')
            ->willReturn($response);

        $this->assertEquals($response, $this->outboundMessageRequestHandler->handle($xml, $documentName));
    }
}