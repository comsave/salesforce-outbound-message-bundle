<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use LogicItLab\Salesforce\MapperBundle\Mapper;
use App\Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use App\Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use App\Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
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
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     * @codeCoverageIgnore
     */
    public function __construct(
        DocumentManager $documentManager,
        Mapper $mapper,
        DocumentUpdater $documentUpdater,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger)
    {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->documentUpdater = $documentUpdater;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
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
            $documentName
        );
    }
}