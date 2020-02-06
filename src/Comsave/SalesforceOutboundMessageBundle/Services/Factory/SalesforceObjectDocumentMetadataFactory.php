<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\SalesforceOutboundMessageBundle\Exception\DocumentNotFoundException;

class SalesforceObjectDocumentMetadataFactory
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

    public function isForceCompared(string $objectName): bool
    {
        return isset($this->documentLocations[$objectName])
            && isset($this->documentLocations[$objectName]['force_compare'])
            && (bool)$this->documentLocations[$objectName]['force_compare'];
    }
}