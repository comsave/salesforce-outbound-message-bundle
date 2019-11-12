<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\SalesforceOutboundMessageBundle\Exception\DocumentNotFoundException;

class OutboundMessageDocumentClassNameFactory
{
    protected $documentLocations;

    public function __construct(array $documentLocations)
    {
        $this->documentLocations = $documentLocations;
    }

    /**
     * @param string $objectName
     * @return string
     * @throws DocumentNotFoundException
     */
    public function getClassName(string $objectName): string
    {
        if (isset($this->documentLocations[$objectName])) {
            return $this->documentLocations[$objectName]['path'];
        }
        throw new DocumentNotFoundException();
    }
}