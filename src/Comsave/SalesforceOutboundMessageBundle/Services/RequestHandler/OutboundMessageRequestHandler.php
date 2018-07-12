<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapResponseBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\OutboundMessageObjectNameRetriever;
use Symfony\Component\HttpFoundation\Response;

class OutboundMessageRequestHandler
{
    /**
     * @var OutboundMessageSoapServerBuilder
     */
    private $outboundMessageSoapServerBuilder;

    /**
     * @var SoapResponseBuilder
     */
    private $soapServerResponseBuilder;

    /**
     * @var OutboundMessageObjectNameRetriever
     */
    private $outboundMessageObjectNameRetriever;

    /**
     * OutboundMessageRequestHandler constructor.
     * @param OutboundMessageSoapServerBuilder $outboundMessageSoapServerBuilder
     * @param SoapResponseBuilder $soapServerResponseBuilder
     * @param OutboundMessageObjectNameRetriever $outboundMessageObjectNameRetriever
     * @codeCoverageIgnore
     */
    public function __construct(
        OutboundMessageSoapServerBuilder $outboundMessageSoapServerBuilder,
        SoapResponseBuilder $soapServerResponseBuilder,
        OutboundMessageObjectNameRetriever $outboundMessageObjectNameRetriever)
    {
        $this->outboundMessageSoapServerBuilder = $outboundMessageSoapServerBuilder;
        $this->soapServerResponseBuilder = $soapServerResponseBuilder;
        $this->outboundMessageObjectNameRetriever = $outboundMessageObjectNameRetriever;
    }

    /**
     * @param string $xml
     * @return Response
     * @throws SalesforceException
     */
    public function handle(string $xml): Response
    {
        $soapServer = $this->outboundMessageSoapServerBuilder->build($this->outboundMessageObjectNameRetriever->retrieve($xml));
        ob_start();
        $soapServer->handle($xml);
        $responseContent = ob_get_contents();
        ob_end_clean();

        return $this->soapServerResponseBuilder->build($responseContent);
    }
}