<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services;

class ObjectComparator
{
    public function equals(object $o1, object $o2): bool
    {
        return (array)$o1 === (array)$o2;
    }
}