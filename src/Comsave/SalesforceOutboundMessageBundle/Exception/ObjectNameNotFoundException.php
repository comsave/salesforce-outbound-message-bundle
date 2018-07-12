<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Exception;

class ObjectNameNotFoundException extends SalesforceException
{
        protected $message = 'Could not read object name from request.';
}