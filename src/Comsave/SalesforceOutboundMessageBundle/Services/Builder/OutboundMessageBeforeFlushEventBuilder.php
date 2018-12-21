<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;

/**
 * Class OutboundMessageBeforeFlushEventBuilder
 * @package Comsave\SalesforceOutboundMessageBundle\Services\Builder
 */
class OutboundMessageBeforeFlushEventBuilder
{
    /**
     * @return OutboundMessageBeforeFlushEvent
     */
    public function build()
    {
        return new OutboundMessageBeforeFlushEvent();
    }
}