<?php

namespace SalesforceOutboundMessageBundle\Services\Builder;

use SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageDocumentClassNameFactory;
use Doctrine\ODM\MongoDB\DocumentManager;
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
     * @var OutboundMessageDocumentClassNameFactory
     */
    private $outboundMessageEntityClassNameFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SoapRequestHandlerBuilder constructor.
     * @param DocumentManager $documentManager
     * @param Mapper $mapper
     * @param DocumentUpdater $documentUpdater
     * @param OutboundMessageDocumentClassNameFactory $outboundMessageEntityClassNameFactory
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     * @codeCoverageIgnore
     */
    public function __construct(
        DocumentManager $documentManager,
        Mapper $mapper,
        DocumentUpdater $documentUpdater,
        OutboundMessageDocumentClassNameFactory $outboundMessageEntityClassNameFactory,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger)
    {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->documentUpdater = $documentUpdater;
        $this->outboundMessageEntityClassNameFactory = $outboundMessageEntityClassNameFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * @param string $objectName
     * @return SoapRequestHandlerInterface
     * @throws \Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException
     */
    public function build(string $objectName): SoapRequestHandlerInterface
    {
        return new SoapRequestHandler(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->eventDispatcher,
            $this->logger,
            $this->outboundMessageEntityClassNameFactory->getClassName($objectName)
        );
    }
}