<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapResponseBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Resolver\OutboundMessageObjectNameResolver;
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
     * @var OutboundMessageObjectNameResolver
     */
    private $outboundMessageObjectNameResolver;

    /**
     * OutboundMessageRequestHandler constructor.
     * @param OutboundMessageSoapServerBuilder $outboundMessageSoapServerBuilder
     * @param SoapResponseBuilder $soapServerResponseBuilder
     * @param OutboundMessageObjectNameResolver $outboundMessageObjectNameResolver
     * @codeCoverageIgnore
     */
    public function __construct(
        OutboundMessageSoapServerBuilder $outboundMessageSoapServerBuilder,
        SoapResponseBuilder $soapServerResponseBuilder,
        OutboundMessageObjectNameResolver $outboundMessageObjectNameResolver
    ) {
        $this->outboundMessageSoapServerBuilder = $outboundMessageSoapServerBuilder;
        $this->soapServerResponseBuilder = $soapServerResponseBuilder;
        $this->outboundMessageObjectNameResolver = $outboundMessageObjectNameResolver;
    }

    /**
     * @param string $xml
     * @return Response
     * @throws SalesforceException
     */
    public function handle(string $xml): Response
    {
        $objectName = $this->outboundMessageObjectNameResolver->resolve($xml);
        $soapServer = $this->outboundMessageSoapServerBuilder->build($objectName);

        ob_start();
        $soapServer->handle($xml);
        $responseContent = ob_get_contents();
        ob_end_clean();

        return $this->soapServerResponseBuilder->build($responseContent);
    }
}