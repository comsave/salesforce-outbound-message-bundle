<?php

namespace Comsave\SalesforceOutboundMessageBundle\Exception;

class ObjectNameNotFoundException extends SalesforceException
{
    public function __construct(string $xml)
    {
        parent::__construct(sprintf('Could not read object name from request. Request was: %s', $xml));
    }
}