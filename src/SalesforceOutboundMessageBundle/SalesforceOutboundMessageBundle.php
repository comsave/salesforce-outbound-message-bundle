<?php

namespace SalesforceOutboundMessageBundle;

use SalesforceOutboundMessageBundle\DependencyInjection\SalesforceOutboundMessageExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SalesforceOutboundMessageBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SalesforceOutboundMessageExtension();
    }

    public function getAlias()
    {
        return 'salesforce_outbound_message';
    }
}