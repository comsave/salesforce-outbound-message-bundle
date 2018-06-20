<?php

namespace SalesforceOutboundMessageBundle\Services\Builder;

use SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory;

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
     * OutboundMessageSoapServerBuilder constructor.
     * @param SoapServerBuilder $soapServerBuilder
     * @param OutboundMessageWsdlPathFactory $wsdlPathFactory
     * @param SoapRequestHandlerBuilder $soapServerRequestHandlerBuilder
     * @codeCoverageIgnore
     */
    public function __construct(
        SoapServerBuilder $soapServerBuilder,
        OutboundMessageWsdlPathFactory $wsdlPathFactory,
        SoapRequestHandlerBuilder $soapServerRequestHandlerBuilder)
    {
        $this->soapServerBuilder = $soapServerBuilder;
        $this->wsdlPathFactory = $wsdlPathFactory;
        $this->soapServerRequestHandlerBuilder = $soapServerRequestHandlerBuilder;
    }

    /**
     * @param string $objectName
     * @return \SoapServer
     */
    public function build(string $objectName): \SoapServer
    {
        return $this->soapServerBuilder->build(
            $this->wsdlPathFactory->getWsdlPath($objectName),
            $this->soapServerRequestHandlerBuilder->build($objectName)
        );
    }
}