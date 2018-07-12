<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Interfaces;

/**
 * Interface WsdlPathFactoryInterface
 * @package SalesforceOutboundMessageBundle\Interfaces
 */
interface WsdlPathFactoryInterface
{
    /**
     * @param string $objectName
     * @return string
     */
    public function getWsdlPath(string $objectName): string;
}