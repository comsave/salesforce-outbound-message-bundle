<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use SoapServer;

class SoapServerBuilder
{
    /** @var array */
    private $soapServerOptions = [
        'classmap' => [
            'notifications' => NotificationRequest::class,
        ],
        'encoding' => 'UTF-8',
    ];

    public function __construct(string $wsdlCache)
    {
        $this->soapServerOptions = array_merge($this->soapServerOptions, [
            'cache_wsdl' => constant($wsdlCache),
        ]);
    }

    /**
     * @param string $wsdlPath
     * @param SoapRequestHandlerInterface $requestHandler
     * @return SoapServer
     */
    public function build(string $wsdlPath, SoapRequestHandlerInterface $requestHandler): SoapServer
    {
        $soapServer = new SoapServer($wsdlPath, $this->soapServerOptions);
        $soapServer->setObject($requestHandler);

        return $soapServer;
    }
}