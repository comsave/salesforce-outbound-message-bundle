<?php

namespace Comsave\SalesforceOutboundMessageBundle\Exception;

class WsdlFileNotFound extends SalesforceException
{
    public function __construct(string $wsdlPath, ?string $objectName = 'Could not find wsdl file.')
    {
        parent::__construct(
            sprintf(
                'WSDL details for object `%s` are not found. Looked for `%s`.',
                $objectName,
                $wsdlPath
            )
        );
    }
}