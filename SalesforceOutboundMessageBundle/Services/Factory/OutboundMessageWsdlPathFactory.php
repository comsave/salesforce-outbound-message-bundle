<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Interfaces\WsdlPathFactoryInterface;

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