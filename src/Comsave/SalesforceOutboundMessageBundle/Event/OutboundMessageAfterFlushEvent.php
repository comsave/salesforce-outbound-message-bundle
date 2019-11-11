<?php

namespace Comsave\SalesforceOutboundMessageBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class OutboundMessageAfterFlushEvent extends Event
{
    const NAME = 'comsave.salesforce_outbound_message.after_flush';

    private $document;

    public function getDocument()
    {
        return $this->document;
    }

    public function setDocument($document): void
    {
        $this->document = $document;
    }
}