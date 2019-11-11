<?php

namespace Comsave\SalesforceOutboundMessageBundle\Interfaces;

interface WsdlPathFactoryInterface
{
    public function getWsdlPath(string $objectName): string;
}