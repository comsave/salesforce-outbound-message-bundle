<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException;

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
     * @throws SalesforceException
     */
    public function getClassName(string $objectName): string
    {
        if(isset($this->documentLocations[$objectName])) {
            return $this->documentLocations[$objectName]['path'];
        }
        throw new SalesforceException('You are trying to access a document that could not be found. Did you forget to add the document path to your config file?');
    }
}