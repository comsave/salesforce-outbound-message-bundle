<?php

namespace Comsave\SalesforceOutboundMessageBundle\Event;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class OutboundMessageBeforeFlushEvent
 * @package Comsave\SalesforceOutboundMessageBundle\Event
 */
class OutboundMessageBeforeFlushEvent extends Event
{
    const NAME = 'comsave.salesforce_outbound_message.before_flush';

    private $newDocument;

    private $existingDocument;

    /**
     * @return mixed
     */
    public function getNewDocument()
    {
        return $this->newDocument;
    }

    /**
     * @param mixed $newDocument
     */
    public function setNewDocument($newDocument): void
    {
        $this->newDocument = $newDocument;
    }

    /**
     * @return mixed
     */
    public function getExistingDocument()
    {
        return $this->existingDocument;
    }

    /**
     * @param mixed $existingDocument
     */
    public function setExistingDocument($existingDocument): void
    {
        $this->existingDocument = $existingDocument;
    }
}