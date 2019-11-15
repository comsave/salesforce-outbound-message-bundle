<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Resolver;

use Comsave\SalesforceOutboundMessageBundle\Exception\ObjectNameNotFoundException;

class OutboundMessageObjectNameResolver
{
    /**
     * @param null|string $xml
     * @return mixed
     * @throws ObjectNameNotFoundException
     */
    public function resolve(?string $xml)
    {
        preg_match("/sObject\sxsi:type=\"sf:([a-z0-9_]+)\"/i", $xml, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        throw new ObjectNameNotFoundException($xml);
    }
}