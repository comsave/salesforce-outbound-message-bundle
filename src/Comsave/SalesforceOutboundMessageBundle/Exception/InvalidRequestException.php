<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Exception;

class InvalidRequestException extends SalesforceException
{
    protected $message = 'Request item is not an object.';
}