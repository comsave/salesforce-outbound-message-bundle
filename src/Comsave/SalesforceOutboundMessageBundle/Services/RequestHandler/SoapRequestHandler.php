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
use Comsave\SalesforceOutboundMessageBundle\Services\ObjectComparator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Annotation\AnnotationReader;
use LogicItLab\Salesforce\MapperBundle\Annotation\Field;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TypeError;

class SoapRequestHandler implements SoapRequestHandlerInterface
{
    /** @var DocumentManager */
    private $documentManager;

    /** @var Mapper */
    private $mapper;

    /** @var DocumentUpdater */
    private $documentUpdater;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var string
     */
    private $documentClassName;

    /** @var bool */
    private $isForceCompared;

    /** @var OutboundMessageBeforeFlushEventBuilder */
    private $outboundMessageBeforeFlushEventBuilder;

    /** @var OutboundMessageAfterFlushEventBuilder */
    private $outboundMessageAfterFlushEventBuilder;

    /** @var ObjectComparator */
    private $objectComparator;

    /** @var AnnotationReader */
    private $salesforceAnnotationReader;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        DocumentManager $documentManager,
        Mapper $mapper,
        DocumentUpdater $documentUpdater,
        EventDispatcherInterface $eventDispatcher,
        string $documentClassName,
        bool $isForceCompared,
        OutboundMessageBeforeFlushEventBuilder $outboundMessageBeforeFlushEventBuilder,
        OutboundMessageAfterFlushEventBuilder $outboundMessageAfterFlushEventBuilder,
        ObjectComparator $objectComparator,
        AnnotationReader $salesforceAnnotationReader
    ) {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->documentUpdater = $documentUpdater;
        $this->eventDispatcher = $eventDispatcher;
        $this->documentClassName = $documentClassName;
        $this->isForceCompared = $isForceCompared;
        $this->outboundMessageBeforeFlushEventBuilder = $outboundMessageBeforeFlushEventBuilder;
        $this->outboundMessageAfterFlushEventBuilder = $outboundMessageAfterFlushEventBuilder;
        $this->objectComparator = $objectComparator;
        $this->salesforceAnnotationReader = $salesforceAnnotationReader;
    }

    /**
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
     * @throws SalesforceException
     * @throws ReflectionException
     * @throws TypeError
     */
    public function process($sObject)
    {
        if (!is_object($sObject)) {
            throw new InvalidRequestException();
        }

        $this->log('Document name: '.$this->documentClassName);
        $this->log('SoapRequestHandler: '.json_encode($sObject));

        $this->documentManager->clear($this->documentClassName);
        $this->mapper->getUnitOfWork()->clear();
        $mappedDocument = $this->mapper->mapToDomainObject($sObject, $this->documentClassName);
        $existingDocument = $this->documentManager->find($this->documentClassName, $mappedDocument->getId());

        if ($this->isForceCompared
            && $existingDocument
            && $this->objectComparator->equals(
                $this->mapper->mapToSalesforceObject($mappedDocument),
                $this->mapper->mapToSalesforceObject($existingDocument)
            )) {
            $this->log('Objects are equal, skipping save');
            return;
        }

        $allowedProperties = $this->getAllowedProperties($this->documentClassName);
        $this->documentUpdater->updateWithDocument($mappedDocument, $existingDocument, null, $allowedProperties);

        $beforeFlushEvent = $this->outboundMessageBeforeFlushEventBuilder->build($mappedDocument, $existingDocument);
        $this->eventDispatcher->dispatch(OutboundMessageBeforeFlushEvent::NAME, $beforeFlushEvent);

        if ($beforeFlushEvent->isSkipDocument()) {
            $this->log('Skipping save');
            return;
        }

        if ($existingDocument) {
            $this->log('saving existing');
            $this->documentUpdater->updateWithDocument($existingDocument, $mappedDocument);
        } else {
            $this->log('saving new');
            $this->documentManager->persist($mappedDocument);
            $existingDocument = $mappedDocument;
        }

        $this->documentManager->flush();

        $afterFlushEvent = $this->outboundMessageAfterFlushEventBuilder->build($existingDocument);
        $this->eventDispatcher->dispatch(OutboundMessageAfterFlushEvent::NAME, $afterFlushEvent);
    }

    public function getAllowedProperties(string $documentClass): array
    {
        /** @var Field[]|ArrayCollection|null $salesforceFields */
        $salesforceFields = $this->salesforceAnnotationReader->getSalesforceFields($documentClass);

        if(!$salesforceFields instanceof ArrayCollection) {
            return [];
        }

        return array_keys($salesforceFields->toArray());
    }

    public function log(string $message): void
    {
        if($this->logger) {
            $this->logger->debug(vsprintf('%s: %s', [
                __CLASS__,
                $message
            ]));
        }
    }

    /** @required */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}