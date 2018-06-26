<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapResponseBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\OutboundMessageObjectNameRetriever;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\OutboundMessageRequestHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OutboundMessageRequestHandlerTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\OutboundMessageRequestHandler
 */
class OutboundMessageRequestHandlerTest extends TestCase
{
    /**
     * @var OutboundMessageRequestHandler
     */
    protected $outboundMessageRequestHandler;

    /**
     * @var MockObject
     */
    private $outboundMessageSoapServerBuilder;

    /**
     * @var MockObject
     */
    private $soapServerResponseBuilder;

    /**
     * @var MockObject
     */
    private $outboundMessageObjectNameRetriever;

    public function setUp()
    {
        $this->outboundMessageSoapServerBuilder = $this->createMock(OutboundMessageSoapServerBuilder::class);
        $this->soapServerResponseBuilder = $this->createMock(SoapResponseBuilder::class);
        $this->outboundMessageObjectNameRetriever = $this->createMock(OutboundMessageObjectNameRetriever::class);

        $this->outboundMessageRequestHandler = new OutboundMessageRequestHandler(
            $this->outboundMessageSoapServerBuilder,
            $this->soapServerResponseBuilder,
            $this->outboundMessageObjectNameRetriever
        );
    }

    public function testHandleSuccess()
    {
        $documentName = 'document/name/folder/file';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';

        $this->outboundMessageObjectNameRetriever->expects($this->once())
            ->method('retrieve')
            ->willReturn('objectName');

        $soapServerMock = $this->createMock(\SoapServer::class);
        $soapServerMock->expects($this->once())
            ->method('handle')
            ->with($xml);

        $this->outboundMessageSoapServerBuilder->expects($this->once())
            ->method('build')
            ->with('objectName')
            ->willReturn($soapServerMock);

        $response = $this->createMock(Response::class);

        $this->soapServerResponseBuilder->expects($this->once())
            ->method('build')
            ->willReturn($response);

        $this->assertEquals($response, $this->outboundMessageRequestHandler->handle($xml, $documentName));
    }
}