<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageAfterFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Comsave\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use Comsave\SalesforceOutboundMessageBundle\Model\NotificationResponse;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageAfterFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use LogicItLab\Salesforce\MapperBundle\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SoapRequestHandlerTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler
 */
class SoapRequestHandlerTest extends TestCase
{
    /**
     * @var SoapRequestHandler
     */
    protected $soapRequestHandler;

    /**
     * @var MockObject|DocumentManager
     */
    private $documentManager;

    /**
     * @var MockObject|Mapper
     */
    private $mapper;

    /**
     * @var MockObject|DocumentUpdater
     */
    private $documentUpdater;

    /**
     * @var MockObject|EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var MockObject|LoggerInterface
     */
    private $logger;

    /**
     * @var MockObject|OutboundMessageBeforeFlushEventBuilder
     */
    private $outboundMessageBeforeFlushEventBuilder;

    /**
     * @var MockObject|OutboundMessageAfterFlushEventBuilder
     */
    private $outboundMessageAfterFlushEventBuilder;

    public function setUp()
    {
        $this->documentManager = $this->createMock(DocumentManager::class);
        $this->mapper = $this->createMock(Mapper::class);
        $this->documentUpdater = $this->createMock(DocumentUpdater::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->outboundMessageBeforeFlushEventBuilder = $this->createMock(OutboundMessageBeforeFlushEventBuilder::class);
        $this->outboundMessageAfterFlushEventBuilder = $this->createMock(OutboundMessageAfterFlushEventBuilder::class);

        $this->soapRequestHandler = new SoapRequestHandler(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->eventDispatcher,
            $this->logger,
            'Product2',
            $this->outboundMessageBeforeFlushEventBuilder,
            $this->outboundMessageAfterFlushEventBuilder
        );
    }

    /**
     * @covers ::notifications()
     * @covers ::process()
     * @expectedException \Comsave\SalesforceOutboundMessageBundle\Exception\SalesforceException
     */
    public function testNotificationsThrowsExceptionWhenNotAnObjectInNotification()
    {
        $notification = new stdClass();
        $notification->sObject = 'not_an_object';

        $notificationRequestMock = $this->createMock(NotificationRequest::class);
        $notificationRequestMock->expects($this->atLeastOnce())
            ->method('getNotification')
            ->willReturn($notification);

        $this->soapRequestHandler->notifications($notificationRequestMock);
    }

    /**
     * @covers ::notifications()
     * @covers ::process()
     */
    public function testNotificationsSuccessfulOnExistingDocument()
    {
        $notification = new stdClass();
        $notification->sObject = new stdClass();

        $notificationRequestMock = $this->createMock(NotificationRequest::class);
        $notificationRequestMock->expects($this->atLeastOnce())
            ->method('getNotification')
            ->willReturn($notification);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $this->mapper->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $mappedDocumentMock = $this->createMock(Product::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getId')
            ->willReturn('897D6FGSD');

        $this->mapper->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $existingDocumentMock = $this->createMock(DocumentInterface::class);

        $beforeFlushEventMock = $this->generateBeforeFlushEventMock($mappedDocumentMock, $existingDocumentMock, false);

        $this->outboundMessageBeforeFlushEventBuilder->expects($this->once())
            ->method('build')
            ->with($mappedDocumentMock, $existingDocumentMock)
            ->willReturn($beforeFlushEventMock);

        $this->eventDispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $this->documentManager->expects($this->once())
            ->method('find')
            ->willReturn($existingDocumentMock);

        $this->documentUpdater->expects($this->once())
            ->method('updateWithDocument');

        $this->documentManager->expects($this->once())
            ->method('flush');

        $afterFlushEventMock = $this->generateAfterFlushEventMock($existingDocumentMock);

        $response = $this->soapRequestHandler->notifications($notificationRequestMock);

        $this->assertInstanceOf(NotificationResponse::class, $response);
        $this->assertTrue($response->getAck());
    }

    /**
     * @covers ::notifications()
     * @covers ::process()
     */
    public function testNotificationsSuccessfulOnNewDocument()
    {
        $notification = new stdClass();
        $notification->sObject = new stdClass();

        $notificationRequestMock = $this->createMock(NotificationRequest::class);
        $notificationRequestMock->expects($this->atLeastOnce())
            ->method('getNotification')
            ->willReturn($notification);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $this->mapper->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $mappedDocumentMock = $this->createMock(Product::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getId')
            ->willReturn('897D6FGSD');

        $this->mapper->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $this->documentManager->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $existingDocumentMock = null;

        $beforeFlushEventMock = $this->generateBeforeFlushEventMock($mappedDocumentMock, $existingDocumentMock, false);

        $this->outboundMessageBeforeFlushEventBuilder->expects($this->once())
            ->method('build')
            ->with($mappedDocumentMock, $existingDocumentMock)
            ->willReturn($beforeFlushEventMock);

        $this->eventDispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $this->documentManager->expects($this->once())
            ->method('persist');

        $this->documentManager->expects($this->once())
            ->method('flush');

        $afterFlushEventMock = $this->generateAfterFlushEventMock($mappedDocumentMock);

        $response = $this->soapRequestHandler->notifications($notificationRequestMock);

        $this->assertInstanceOf(NotificationResponse::class, $response);
        $this->assertTrue($response->getAck());
    }

    /**
     * @covers ::notifications()
     * @covers ::process()
     */
    public function testNotificationsSkipSuccessful()
    {
        $notification = new stdClass();
        $notification->sObject = new stdClass();

        $notificationRequestMock = $this->createMock(NotificationRequest::class);
        $notificationRequestMock->expects($this->atLeastOnce())
            ->method('getNotification')
            ->willReturn($notification);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $this->mapper->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $mappedDocumentMock = $this->createMock(Product::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getId')
            ->willReturn('897D6FGSD');

        $this->mapper->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $this->documentManager->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $existingDocumentMock = null;

        $beforeFlushEventMock = $this->generateBeforeFlushEventMock($mappedDocumentMock, $existingDocumentMock, true);

        $this->outboundMessageBeforeFlushEventBuilder->expects($this->once())
            ->method('build')
            ->with($mappedDocumentMock, $existingDocumentMock)
            ->willReturn($beforeFlushEventMock);

        $this->eventDispatcher->expects($this->exactly(1))
            ->method('dispatch');

        $this->documentManager->expects($this->never())
            ->method('persist');

        $this->documentManager->expects($this->never())
            ->method('flush');

        $afterFlushEventMock = $this->generateAfterFlushEventMock($mappedDocumentMock);

        $response = $this->soapRequestHandler->notifications($notificationRequestMock);

        $this->assertInstanceOf(NotificationResponse::class, $response);
        $this->assertTrue($response->getAck());
    }

    public function generateBeforeFlushEventMock($mappedDocumentMock, $existingDocumentMock, $isSkipDocument = false)
    {
        $eventMock = $this->createMock(OutboundMessageBeforeFlushEvent::class);

        $eventMock->expects($this->any())
            ->method('getNewDocument')
            ->with($mappedDocumentMock);
        $eventMock->expects($this->any())
            ->method('getExistingDocument')
            ->with($existingDocumentMock);
        $eventMock->expects($this->any())
            ->method('isSkipDocument')
            ->willReturn($isSkipDocument);

        return $eventMock;
    }

    public function generateAfterFlushEventMock($documentMock)
    {
        $eventMock = $this->createMock(OutboundMessageAfterFlushEvent::class);

        $eventMock->expects($this->any())
            ->method('getDocument')
            ->with($documentMock);

        return $eventMock;
    }
}
