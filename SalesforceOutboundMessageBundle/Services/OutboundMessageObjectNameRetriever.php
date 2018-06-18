<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services;

use Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException;

/**
 * Class OutboundMessageObjectNameRetriever
 * @package Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services
 */
class OutboundMessageObjectNameRetriever
{
    /**
     * @param string $xml
     * @return string
     * @throws SalesforceException
     */
    public function retrieve(?string $xml)
    {
        preg_match("/sObject\sxsi:type=\"sf:([a-z0-9_]+)\"/i", $xml, $matches);

        if (isset($matches[1])) return $matches[1];

        throw new SalesforceException('Could not read object name from request.');
    }
}