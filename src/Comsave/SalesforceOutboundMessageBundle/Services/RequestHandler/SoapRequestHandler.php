<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageAfterFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Exception\InvalidRequestException;
use Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException;
use Comsave\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use Comsave\SalesforceOutboundMessageBundle\Model\NotificationResponse;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageAfterFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TypeError;

class SoapRequestHandler implements SoapRequestHandlerInterface
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
     * @var string
     */
    private $documentClassName;

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
     * @param string $documentClassName
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
        string $documentClassName,
        OutboundMessageBeforeFlushEventBuilder $outboundMessageBeforeFlushEventBuilder,
        OutboundMessageAfterFlushEventBuilder $outboundMessageAfterFlushEventBuilder
    ) {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->documentUpdater = $documentUpdater;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->documentClassName = $documentClassName;
        $this->outboundMessageBeforeFlushEventBuilder = $outboundMessageBeforeFlushEventBuilder;
        $this->outboundMessageAfterFlushEventBuilder = $outboundMessageAfterFlushEventBuilder;
    }

    /**
     * @param NotificationRequest $request
     * @return NotificationResponse
     * @throws SalesforceException
     * @throws ReflectionException
     * @throws TypeError
     */
    public function notifications(NotificationRequest $request): NotificationResponse
    {
        $notifications = is_array($request->getNotification()) ? $request->getNotification() : [$request->getNotification()];

        foreach ($notifications as $notification) {
            $this->process($notification->sObject);
        }

        return (new NotificationResponse())->setAck(true);
    }

    /**
     * @param $sObject
     * @throws SalesforceException
     * @throws ReflectionException
     * @throws TypeError
     */
    public function process($sObject)
    {
        if (!is_object($sObject)) {
            throw new InvalidRequestException();
        }

        $this->logger->debug('Document name: '.$this->documentClassName);
        $this->logger->debug('SoapRequestHandler: '.json_encode($sObject));

        $this->mapper->getUnitOfWork()->clear();
        $mappedDocument = $this->mapper->mapToDomainObject($sObject, $this->documentClassName);
        $existingDocument = $this->documentManager->find($this->documentClassName, $mappedDocument->getId());

        $beforeFlushEvent = $this->outboundMessageBeforeFlushEventBuilder->build($mappedDocument, $existingDocument);
        $this->eventDispatcher->dispatch(OutboundMessageBeforeFlushEvent::NAME, $beforeFlushEvent);

        if ($beforeFlushEvent->isSkipDocument()) {
            $this->logger->info('Skipping save');

            return;
        }

        if ($beforeFlushEvent->isDeleteDocument()) {
            $this->logger->info('deleting document');
            $this->documentManager->remove($existingDocument);
        } else if ($existingDocument) {
            $this->logger->info('saving existing');
            $this->documentUpdater->updateWithDocument($existingDocument, $mappedDocument);
        } else {
            $this->logger->info('saving new');
            $this->documentManager->persist($mappedDocument);
            $existingDocument = $mappedDocument;
        }

        $this->documentManager->flush();

        $afterFlushEvent = $this->outboundMessageAfterFlushEventBuilder->build($existingDocument);
        $this->eventDispatcher->dispatch(OutboundMessageAfterFlushEvent::NAME, $afterFlushEvent);
    }
}