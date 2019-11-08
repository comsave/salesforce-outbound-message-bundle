<?php

namespace Comsave\SalesforceOutboundMessageBundle\Event;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class OutboundMessageAfterFlushEvent
 * @package Comsave\SalesforceOutboundMessageBundle\Event
 */
class OutboundMessageAfterFlushEvent extends Event
{
    const NAME = 'comsave.salesforce_outbound_message.after_flush';

    private $document;

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document): void
    {
        $this->document = $document;
    }
}