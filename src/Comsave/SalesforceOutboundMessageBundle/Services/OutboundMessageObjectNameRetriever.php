<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services;

use Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException;

/**
 * Class OutboundMessageObjectNameRetriever
 * @package Comsave\SalesforceOutboundMessageBundle\Services
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