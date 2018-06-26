<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Factory;

class OutboundMessageDocumentClassNameFactory
{
    /**
     * @var array
     */
    protected $documentLocations;

    public function __construct(array $documentLocations)
    {
        $this->documentLocations = $documentLocations;
    }
    /**
     * @param string $objectName
     * @return string
     */
    public function getClassName(string $objectName): string
    {
        return $this->documentLocations[$objectName]['path'];
    }
}