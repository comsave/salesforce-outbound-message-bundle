<?php

namespace Comsave\SalesforceOutboundMessageBundle\Exception;

use Throwable;

class WsdlFileNotFound extends SalesforceException
{
    /**
     * @var string
     */
    protected $message;

    public function __construct(string $objectname = "Could not find wsdl file.", string $wsdlPath)
    {
        $this->message = sprintf('WSDL details for object `%s` are not found. Looked for: %s.', $objectname, $wsdlPath);
    }
}