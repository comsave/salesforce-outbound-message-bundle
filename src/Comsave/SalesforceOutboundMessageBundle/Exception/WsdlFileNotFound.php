<?php

namespace Comsave\SalesforceOutboundMessageBundle\Exception;

use Throwable;

class WsdlFileNotFound extends SalesforceException
{
    public function __construct(string $message = "Could not find wsdl file.", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}