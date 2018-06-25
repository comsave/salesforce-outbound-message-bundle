<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageDocumentClassNameFactory;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory;

class OutboundMessageSoapServerBuilder
{
    /**
     * @var SoapServerBuilder
     */
    private $soapServerBuilder;

    /**
     * @var OutboundMessageWsdlPathFactory
     */
    private $wsdlPathFactory;

    /**
     * @var SoapRequestHandlerBuilder
     */
    private $soapServerRequestHandlerBuilder;

    /**
     * @var OutboundMessageDocumentClassNameFactory
     */
    private $outboundMessageDocumentClassNameFactory;

    /**
     * @param SoapServerBuilder $soapServerBuilder
     * @param OutboundMessageWsdlPathFactory $wsdlPathFactory
     * @param SoapRequestHandlerBuilder $soapServerRequestHandlerBuilder
     * @param OutboundMessageDocumentClassNameFactory $outboundMessageDocumentClassNameFactory
     * @codeCoverageIgnore
     */
    public function __construct(SoapServerBuilder $soapServerBuilder, OutboundMessageWsdlPathFactory $wsdlPathFactory, SoapRequestHandlerBuilder $soapServerRequestHandlerBuilder, OutboundMessageDocumentClassNameFactory $outboundMessageDocumentClassNameFactory)
    {
        $this->soapServerBuilder = $soapServerBuilder;
        $this->wsdlPathFactory = $wsdlPathFactory;
        $this->soapServerRequestHandlerBuilder = $soapServerRequestHandlerBuilder;
        $this->outboundMessageDocumentClassNameFactory = $outboundMessageDocumentClassNameFactory;
    }

    /**
     * @param string $objectName
     * @return \SoapServer
     * @throws \SalesforceOutboundMessageBundle\Exception\SalesforceException
     */
    public function build(string $objectName): \SoapServer
    {
        return $this->soapServerBuilder->build(
            $this->wsdlPathFactory->getWsdlPath($objectName),
            $this->soapServerRequestHandlerBuilder->build($this->outboundMessageDocumentClassNameFactory->getClassName($objectName))
        );
    }
}