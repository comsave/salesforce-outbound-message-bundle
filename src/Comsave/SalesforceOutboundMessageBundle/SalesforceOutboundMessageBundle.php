<?php

namespace Comsave\SalesforceOutboundMessageBundle;

use Comsave\SalesforceOutboundMessageBundle\DependencyInjection\SalesforceOutboundMessageExtension;
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