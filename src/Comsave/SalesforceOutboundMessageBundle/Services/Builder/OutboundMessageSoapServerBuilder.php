<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\SalesforceObjectDocumentMetadataFactory;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageWsdlPathFactory;
use SoapServer;

class OutboundMessageSoapServerBuilder
{
    /** @var SoapServerBuilder */
    private $soapServerBuilder;

    /** @var OutboundMessageWsdlPathFactory */
    private $wsdlPathFactory;

    /**
     * @var SoapRequestHandlerBuilder
     */
    private $soapServerRequestHandlerBuilder;

    /**
     * @var SalesforceObjectDocumentMetadataFactory
     */
    private $salesforceObjectMetadataFactory;

    /**
     * OutboundMessageSoapServerBuilder constructor.
     * @param SoapServerBuilder $soapServerBuilder
     * @param OutboundMessageWsdlPathFactory $wsdlPathFactory
     * @param SoapRequestHandlerBuilder $soapServerRequestHandlerBuilder
     * @param SalesforceObjectDocumentMetadataFactory $outboundMessageDocumentClassNameFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        SoapServerBuilder $soapServerBuilder,
        OutboundMessageWsdlPathFactory $wsdlPathFactory,
        SoapRequestHandlerBuilder $soapServerRequestHandlerBuilder,
        SalesforceObjectDocumentMetadataFactory $outboundMessageDocumentClassNameFactory
    ) {
        $this->soapServerBuilder = $soapServerBuilder;
        $this->wsdlPathFactory = $wsdlPathFactory;
        $this->soapServerRequestHandlerBuilder = $soapServerRequestHandlerBuilder;
        $this->salesforceObjectMetadataFactory = $outboundMessageDocumentClassNameFactory;
    }

    /**
     * @param string $objectName
     * @return SoapServer
     * @throws SalesforceException
     */
    public function build(string $objectName): SoapServer
    {
        return $this->soapServerBuilder->build(
            $this->wsdlPathFactory->getWsdlPath($objectName),
            $this->soapServerRequestHandlerBuilder->build(
                $this->salesforceObjectMetadataFactory->getClassName($objectName),
                $this->salesforceObjectMetadataFactory->isForceCompared($objectName)
            )
        );
    }
}