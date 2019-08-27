<?php

namespace Comsave\SalesforceOutboundMessageBundle\Exception;

class ObjectNameNotFoundException extends SalesforceException
{
    public function __construct(string $xml)
    {
        $this->message = sprintf('Could not read object name from request. Request was: %s', $xml);
    }
}