<?php

namespace SalesforceOutboundMessageBundle\Services\Factory;

use SalesforceOutboundMessageBundle\Interfaces\WsdlPathFactoryInterface;
use SalesforceOutboundMessageBundle\Exception\SalesforceException;
use Symfony\Component\DependencyInjection\Container;

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
            throw new SalesforceException(sprintf('WSDL details for object `%s` are not found.', $objectName));
        }

        return $wsdlPath;
    }
}