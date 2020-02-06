<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\ObjectComparator;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SoapRequestHandlerBuilder
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var DocumentUpdater
     */
    private $documentUpdater;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var OutboundMessageBeforeFlushEventBuilder
     */
    private $outboundMessageBeforeFlushEventBuilder;

    /**
     * @var OutboundMessageAfterFlushEventBuilder
     */
    private $outboundMessageAfterFlushEventBuilder;

    /** @var ObjectComparator */
    private $objectComparator;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        DocumentManager $documentManager,
        Mapper $mapper,
        DocumentUpdater $documentUpdater,
        EventDispatcherInterface $eventDispatcher,
        OutboundMessageBeforeFlushEventBuilder $outboundMessageBeforeFlushEventBuilder,
        OutboundMessageAfterFlushEventBuilder $outboundMessageAfterFlushEventBuilder,
        ObjectComparator $objectComparator
    ) {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->documentUpdater = $documentUpdater;
        $this->eventDispatcher = $eventDispatcher;
        $this->outboundMessageBeforeFlushEventBuilder = $outboundMessageBeforeFlushEventBuilder;
        $this->outboundMessageAfterFlushEventBuilder = $outboundMessageAfterFlushEventBuilder;
        $this->objectComparator = $objectComparator;
    }

    public function build(string $documentName, bool $isForceCompared): SoapRequestHandlerInterface
    {
        return new SoapRequestHandler(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->eventDispatcher,
            $documentName,
            $isForceCompared,
            $this->outboundMessageBeforeFlushEventBuilder,
            $this->outboundMessageAfterFlushEventBuilder,
            $this->objectComparator
        );
    }
}