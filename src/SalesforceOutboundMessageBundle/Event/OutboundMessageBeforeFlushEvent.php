<?php

namespace SalesforceOutboundMessageBundle\Event;

use LogicItLab\Salesforce\MapperBundle\Model\AbstractModel;
use SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

class OutboundMessageBeforeFlushEvent extends Event
{
    const NAME = 'comsave.webservice.core.salesforce_outbound_message.before_flush';

    /**
     * @var AbstractModel
     */
    private $document;

    /**
     * @var string
     */
    private $pricebookEntryId;

    /**
     * @return AbstractModel
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param AbstractModel $document
     */
    public function setDocument(AbstractModel $document): void
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