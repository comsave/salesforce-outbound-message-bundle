<?php

namespace Comsave\SalesforceOutboundMessageBundle\Exception;

class InvalidRequestException extends SalesforceException
{
    protected $message = 'Request item is not an object.';
}