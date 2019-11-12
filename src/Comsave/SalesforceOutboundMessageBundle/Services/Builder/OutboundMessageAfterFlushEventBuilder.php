<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageAfterFlushEvent;

class OutboundMessageAfterFlushEventBuilder
{
    public function build($document)
    {
        $event = new OutboundMessageAfterFlushEvent();
        $event->setDocument($document);

        return $event;
    }
}