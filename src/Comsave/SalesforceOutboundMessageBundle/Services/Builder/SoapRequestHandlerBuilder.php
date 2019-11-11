<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OutboundMessageBeforeFlushEventBuilder
     */
    private $outboundMessageBeforeFlushEventBuilder;

    /**
     * @var OutboundMessageAfterFlushEventBuilder
     */
    private $outboundMessageAfterFlushEventBuilder;

    /**
     * @param DocumentManager $documentManager
     * @param Mapper $mapper
     * @param DocumentUpdater $documentUpdater
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     * @param OutboundMessageBeforeFlushEventBuilder $outboundMessageBeforeFlushEventBuilder
     * @param OutboundMessageAfterFlushEventBuilder $outboundMessageAfterFlushEventBuilder
     * @codeCoverageIgnore
     */
    public function __construct(
        DocumentManager $documentManager,
        Mapper $mapper,
        DocumentUpdater $documentUpdater,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger,
        OutboundMessageBeforeFlushEventBuilder $outboundMessageBeforeFlushEventBuilder,
        OutboundMessageAfterFlushEventBuilder $outboundMessageAfterFlushEventBuilder
    ) {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->documentUpdater = $documentUpdater;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->outboundMessageBeforeFlushEventBuilder = $outboundMessageBeforeFlushEventBuilder;
        $this->outboundMessageAfterFlushEventBuilder = $outboundMessageAfterFlushEventBuilder;
    }

    /**
     * @param string $documentName
     * @return SoapRequestHandlerInterface
     */
    public function build(string $documentName): SoapRequestHandlerInterface
    {
        return new SoapRequestHandler(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->eventDispatcher,
            $this->logger,
            $documentName,
            $this->outboundMessageBeforeFlushEventBuilder,
            $this->outboundMessageAfterFlushEventBuilder
        );
    }
}