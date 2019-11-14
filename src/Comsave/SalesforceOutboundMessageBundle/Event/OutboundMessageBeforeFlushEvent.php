<?php

namespace Comsave\SalesforceOutboundMessageBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class OutboundMessageBeforeFlushEvent extends Event
{
    const NAME = 'comsave.salesforce_outbound_message.before_flush';

    private $newDocument;

    private $existingDocument;

    private $skipDocument = false;

    public function getNewDocument()
    {
        return $this->newDocument;
    }

    public function setNewDocument($newDocument): void
    {
        $this->newDocument = $newDocument;
    }

    public function getExistingDocument()
    {
        return $this->existingDocument;
    }

    public function setExistingDocument($existingDocument): void
    {
        $this->existingDocument = $existingDocument;
    }

    public function isSkipDocument(): bool
    {
        return $this->skipDocument;
    }

    public function setSkipDocument(bool $skipDocument): void
    {
        $this->skipDocument = $skipDocument;
    }
}