<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Interfaces;

use Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException;

interface WsdlPathFactoryInterface
{
    /**
     * @param string $objectName
     * @return string
     * @throws SalesforceException
     */
    public function getWsdlPath(string $objectName): string;
}