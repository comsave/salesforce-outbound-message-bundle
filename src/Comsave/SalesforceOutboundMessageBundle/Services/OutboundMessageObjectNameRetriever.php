<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services;

use Comsave\SalesforceOutboundMessageBundle\Exception\ObjectNameNotFoundException;

/**
 * Class OutboundMessageObjectNameRetriever
 * @package Comsave\SalesforceOutboundMessageBundle\Services
 */
class OutboundMessageObjectNameRetriever
{
    /**
     * @param null|string $xml
     * @return mixed
     * @throws ObjectNameNotFoundException
     */
    public function retrieve(?string $xml)
    {
        preg_match("/sObject\sxsi:type=\"sf:([a-z0-9_]+)\"/i", $xml, $matches);

        if (isset($matches[1])) return $matches[1];

        throw new ObjectNameNotFoundException($xml);
    }
}