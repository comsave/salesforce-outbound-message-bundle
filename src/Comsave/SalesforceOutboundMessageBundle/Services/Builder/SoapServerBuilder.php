<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use SoapServer;

class SoapServerBuilder
{
    private const SOAP_SERVER_PROPERTIES = [
        'classmap' => [
            'notifications' => NotificationRequest::class,
        ],
        'encoding' => 'UTF-8',
    ];

    /**
     * @param string $wsdlPath
     * @param SoapRequestHandlerInterface $requestHandler
     * @return SoapServer
     */
    public function build(string $wsdlPath, SoapRequestHandlerInterface $requestHandler): SoapServer
    {
        $soapServer = new SoapServer($wsdlPath, static::SOAP_SERVER_PROPERTIES);
        $soapServer->setObject($requestHandler);

        return $soapServer;
    }
}