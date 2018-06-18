<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\Webservice\Core\CoreBundle\Services\DocumentUpdater;
use Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Interfaces\SoapRequestHandlerInterface;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Model\NotificationResponse;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use Psr\Log\LoggerInterface;

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
     * @var
     */
    private $documentClassName;

    /**
     * SoapRequestHandler constructor.
     * @param DocumentManager $documentManager
     * @param Mapper $mapper
     * @param DocumentUpdater $documentUpdater
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     * @param $documentClassName
     * @codeCoverageIgnore
     */
    public function __construct(
        DocumentManager $documentManager,
        Mapper $mapper,
        DocumentUpdater $documentUpdater,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger,
        $documentClassName)
    {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->documentUpdater = $documentUpdater;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->documentClassName = $documentClassName;
    }

    /**
     * @param NotificationRequest $request
     * @return NotificationResponse
     * @throws SalesforceException
     * @throws \ReflectionException
     * @throws \TypeError
     */
    public function notifications(NotificationRequest $request): NotificationResponse
    {
        $notifications = is_array($request->getNotification()) ? $request->getNotification() : [$request->getNotification()];

        foreach ($notifications as $notification) {
            $this->process($notification->sObject);
        }

        return (new NotificationResponse())->setAct(true);
    }

    /**
     * @param $sObject
     * @throws SalesforceException
     * @throws \ReflectionException
     * @throws \TypeError
     */
    public function process($sObject)
    {
        if (!is_object($sObject)) {
            throw new SalesforceException('Request item is not an object.');
        }

        $this->logger->debug('Document name: ' . $this->documentClassName);
        $this->logger->debug('SoapRequestHandler: ' . \json_encode($sObject));

        $mappedDocument = $this->mapper->mapToDomainObject($sObject, $this->documentClassName);
        $existingDocument = $this->documentManager->find($this->documentClassName, $mappedDocument->getId());

        $beforeFlushEvent = new OutboundMessageBeforeFlushEvent();
        $beforeFlushEvent->setDocument($mappedDocument);
        $this->eventDispatcher->dispatch(OutboundMessageBeforeFlushEvent::NAME, $beforeFlushEvent);

        if ($mappedDocument->getName() != 'Skip') {
            if ($existingDocument) {
                $this->logger->info('saving existing');
                $this->documentUpdater->updateWithDocument($existingDocument, $mappedDocument);
            } else {
                $this->logger->info('saving new');
                $this->documentManager->persist($mappedDocument);
            }

            $this->documentManager->flush();
        } else {
            $this->logger->info('Skipping save');
        }
    }
}