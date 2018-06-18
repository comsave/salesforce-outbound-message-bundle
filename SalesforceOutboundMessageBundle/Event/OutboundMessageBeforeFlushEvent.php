<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Event;

use Comsave\Webservice\Core\CoreBundle\Document\BaseDocument;
use Comsave\Webservice\Core\CoreBundle\Interfaces\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

class OutboundMessageBeforeFlushEvent extends Event
{
    const NAME = 'comsave.webservice.core.salesforce_outbound_message.before_flush';

    /**
     * @var DocumentInterface
     */
    private $document;

    /**
     * @var string
     */
    private $pricebookEntryId;

    /**
     * @return DocumentInterface
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param DocumentInterface $document
     */
    public function setDocument(DocumentInterface $document): void
    {
        $this->document = $document;
    }

    /**
     * @return string
     */
    public function getPricebookEntryId(): string
    {
        return $this->pricebookEntryId;
    }

    /**
     * @param string $pricebookEntryId
     */
    public function setPricebookEntryId(string $pricebookEntryId): void
    {
        $this->pricebookEntryId = $pricebookEntryId;
    }
}