<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Services\Factory;

use App\Comsave\SalesforceOutboundMessageBundle\Exception\DocumentNotFoundException;

/**
 * Class OutboundMessageDocumentClassNameFactory
 * @package Comsave\SalesforceOutboundMessageBundle\Services\Factory
 */
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
     * @throws DocumentNotFoundException
     */
    public function getClassName(string $objectName): string
    {
        if(isset($this->documentLocations[$objectName])) {
            return $this->documentLocations[$objectName]['path'];
        }
        throw new DocumentNotFoundException();
    }
}