<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;

/**
 * Class OutboundMessageBeforeFlushEventBuilder
 * @package Comsave\SalesforceOutboundMessageBundle\Services\Builder
 */
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