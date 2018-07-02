<?php

namespace Comsave\SalesforceOutboundMessageBundle\Event;

use LogicItLab\Salesforce\MapperBundle\Model\AbstractModel;
use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

class OutboundMessageBeforeFlushEvent extends Event
{
    const NAME = 'comsave.salesforce_outbound_message.before_flush';

    /**
     * @var DocumentInterface
     */
    private $document;

    /**
     * @var array
     */
    private $extraInfo;

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
     * @return array
     */
    public function getExtraInfo(): array
    {
        return $this->extraInfo;
    }

    /**
     * @param array $extraInfo
     */
    public function setExtraInfo(array $extraInfo): void
    {
        $this->extraInfo = $extraInfo;
    }
}