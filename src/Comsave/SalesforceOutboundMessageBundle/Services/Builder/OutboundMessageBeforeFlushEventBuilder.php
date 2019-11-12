<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;

class OutboundMessageBeforeFlushEventBuilder
{
    public function build($newDocument, $existingDocument)
    {
        $event = new OutboundMessageBeforeFlushEvent();
        $event->setNewDocument($newDocument);
        $event->setExistingDocument($existingDocument);

        return $event;
    }
}