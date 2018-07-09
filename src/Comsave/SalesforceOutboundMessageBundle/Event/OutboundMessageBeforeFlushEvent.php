<?php

namespace Comsave\SalesforceOutboundMessageBundle\Event;

use LogicItLab\Salesforce\MapperBundle\Model\AbstractModel;
use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class OutboundMessageBeforeFlushEvent
 * @package Comsave\SalesforceOutboundMessageBundle\Event
 */
class OutboundMessageBeforeFlushEvent extends Event
{
    const NAME = 'comsave.salesforce_outbound_message.before_flush';

    /**
     * @var DocumentInterface
     */
    private $document;

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
}