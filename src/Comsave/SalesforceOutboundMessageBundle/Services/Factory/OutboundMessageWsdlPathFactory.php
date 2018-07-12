<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Services\Factory;

use App\Comsave\SalesforceOutboundMessageBundle\Exception\WsdlFileNotFound;
use App\Comsave\SalesforceOutboundMessageBundle\Interfaces\WsdlPathFactoryInterface;
use App\Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException;

class OutboundMessageWsdlPathFactory implements WsdlPathFactoryInterface
{
    private $abstractWsdlPath;

    public function __construct(string $wsdlPath)
    {
        $this->abstractWsdlPath = $wsdlPath;
    }

    /**
     * @param string $objectName
     * @return string
     * @throws SalesforceException
     */
    public function getWsdlPath(string $objectName): string
    {
        if (substr($this->abstractWsdlPath, -1, 1) != '/') {
            $this->abstractWsdlPath .= '/';
        }

        $wsdlPath = sprintf('%s%s.wsdl', $this->abstractWsdlPath, $objectName);

        if (!file_exists($wsdlPath)) {
            throw new WsdlFileNotFound($objectName, $wsdlPath);
        }

        return $wsdlPath;
    }
}