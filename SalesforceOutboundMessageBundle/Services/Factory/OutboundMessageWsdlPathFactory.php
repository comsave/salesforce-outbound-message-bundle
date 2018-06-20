<?php

namespace SalesforceOutboundMessageBundle\Services\Factory;

use SalesforceOutboundMessageBundle\Interfaces\WsdlPathFactoryInterface;
use SalesforceOutboundMessageBundle\Exception\SalesforceException;

class OutboundMessageWsdlPathFactory implements WsdlPathFactoryInterface
{
    private $abstractWsdlPath = __DIR__ . '/../../Resources/wsdl/%s.wsdl';

    /**
     * @param string $objectName
     * @return string
     * @throws SalesforceException
     */
    public function getWsdlPath(string $objectName): string
    {
        $wsdlPath = sprintf($this->abstractWsdlPath, $objectName);

        if(!file_exists($wsdlPath)) {
            throw new SalesforceException(sprintf('WSDL details for object `%s` are not found.', $objectName));
        }

        return $wsdlPath;
    }
}