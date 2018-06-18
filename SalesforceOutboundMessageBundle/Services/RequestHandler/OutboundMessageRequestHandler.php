<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Model\NotificationResponse;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder\SoapResponseBuilder;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageSoapServerBuilder;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\OutboundMessageObjectNameRetriever;
use Psr\Log\LoggerInterface;
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