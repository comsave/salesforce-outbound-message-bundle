<?php

namespace Comsave\SalesforceOutboundMessageBundle\EventSubscriber;

use Comsave\SalesforceOutboundMessageBundle\Document\ObjectToBeRemoved;
use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageDocumentClassNameFactory;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Throwable;

class ObjectToBeRemovedEventSubscriber implements EventSubscriberInterface
{
    /** @var DocumentManager */
    private $documentManager;

    /** @var Mapper */
    private $mapper;

    /** @var OutboundMessageDocumentClassNameFactory */
    private $outboundMessageDocumentClassNameFactory;

    /**
     * @param DocumentManager $documentManager
     * @param Mapper $mapper
     * @param OutboundMessageDocumentClassNameFactory $outboundMessageDocumentClassNameFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        DocumentManager $documentManager,
        Mapper $mapper,
        OutboundMessageDocumentClassNameFactory $outboundMessageDocumentClassNameFactory
    ) {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
        $this->outboundMessageDocumentClassNameFactory = $outboundMessageDocumentClassNameFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OutboundMessageBeforeFlushEvent::NAME => [
                ['onBeforeFlush'],
            ],
        ];
    }

    public function supports(DocumentInterface $document): bool
    {
        $documentClass = get_class($document);

        return ObjectToBeRemoved::class == $documentClass || $document instanceof ObjectToBeRemoved;
    }

    /**
     * @param OutboundMessageBeforeFlushEvent $event
     * @throws Exception
     */
    public function onBeforeFlush(OutboundMessageBeforeFlushEvent $event): void
    {
        /* @var ObjectToBeRemoved $objectToBeRemoved */
        $objectToBeRemoved = $event->getNewDocument();

        if (!$this->supports($objectToBeRemoved)) {
            return;
        }

        $event->setSkipDocument(true);

        $removableDocumentClass = $this->outboundMessageDocumentClassNameFactory->getClassName($objectToBeRemoved->getObjectClass());
        $removableDocumentRepository = $this->documentManager->getRepository($removableDocumentClass);

        $removableDocument = $removableDocumentRepository->find($objectToBeRemoved->getObjectId());

        if ($removableDocument) {
            $this->documentManager->remove($removableDocument);
            $this->documentManager->flush();
        }

        try {
            $this->mapper->delete([$objectToBeRemoved]);
        } catch (Throwable $ex) {
            // Quit silently if not available for removal anymore
        }
    }
}