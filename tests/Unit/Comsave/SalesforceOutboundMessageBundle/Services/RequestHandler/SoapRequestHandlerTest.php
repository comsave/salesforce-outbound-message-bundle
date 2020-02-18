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
use Comsave\SalesforceOutboundMessageBundle\Services\ObjectComparator;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use LogicItLab\Salesforce\MapperBundle\Annotation\AnnotationReader;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use LogicItLab\Salesforce\MapperBundle\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tests\Stub\DocumentWithSalesforceFields;

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
    private $documentManagerMock;

    /**
     * @var MockObject|Mapper
     */
    private $mapperMock;

    /**
     * @var MockObject|DocumentUpdater
     */
    private $documentUpdaterMock;

    /**
     * @var MockObject|EventDispatcherInterface
     */
    private $eventDispatcherMock;

    /**
     * @var MockObject|OutboundMessageBeforeFlushEventBuilder
     */
    private $outboundMessageBeforeFlushEventBuilderMock;

    /**
     * @var MockObject|OutboundMessageAfterFlushEventBuilder
     */
    private $outboundMessageAfterFlushEventBuilderMock;

    /** @var ObjectComparator|MockObject */
    private $objectComparatorMock;

    /** @var AnnotationReader|MockObject */
    private $salesforceAnnotationReaderMock;

    public function setUp()
    {
        $this->documentManagerMock = $this->createMock(DocumentManager::class);
        $this->mapperMock = $this->createMock(Mapper::class);
        $this->documentUpdaterMock = $this->createMock(DocumentUpdater::class);
        $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $this->outboundMessageBeforeFlushEventBuilderMock = $this->createMock(OutboundMessageBeforeFlushEventBuilder::class);
        $this->outboundMessageAfterFlushEventBuilderMock = $this->createMock(OutboundMessageAfterFlushEventBuilder::class);
        $this->objectComparatorMock = $this->createMock(ObjectComparator::class);
        $this->salesforceAnnotationReaderMock = $this->createMock(AnnotationReader::class);

        $this->soapRequestHandler = new SoapRequestHandler(
            $this->documentManagerMock,
            $this->mapperMock,
            $this->documentUpdaterMock,
            $this->eventDispatcherMock,
            'Product2',
            false,
            $this->outboundMessageBeforeFlushEventBuilderMock,
            $this->outboundMessageAfterFlushEventBuilderMock,
            $this->objectComparatorMock,
            $this->salesforceAnnotationReaderMock
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
        $this->mapperMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $mappedDocumentMock = $this->createMock(Product::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getId')
            ->willReturn('897D6FGSD');

        $this->mapperMock->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $existingDocumentMock = $this->createMock(DocumentInterface::class);

        $beforeFlushEventMock = $this->generateBeforeFlushEventMock($mappedDocumentMock, $existingDocumentMock, false);

        $this->outboundMessageBeforeFlushEventBuilderMock->expects($this->once())
            ->method('build')
            ->with($mappedDocumentMock, $existingDocumentMock)
            ->willReturn($beforeFlushEventMock);

        $this->eventDispatcherMock->expects($this->exactly(2))
            ->method('dispatch');

        $this->documentManagerMock->expects($this->once())
            ->method('find')
            ->willReturn($existingDocumentMock);

        $this->documentUpdaterMock->expects($this->exactly(2))
            ->method('updateWithDocument');

        $this->documentManagerMock->expects($this->once())
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
        $this->mapperMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $mappedDocumentMock = $this->createMock(Product::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getId')
            ->willReturn('897D6FGSD');

        $this->mapperMock->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $this->documentManagerMock->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $existingDocumentMock = null;

        $beforeFlushEventMock = $this->generateBeforeFlushEventMock($mappedDocumentMock, $existingDocumentMock, false);

        $this->outboundMessageBeforeFlushEventBuilderMock->expects($this->once())
            ->method('build')
            ->with($mappedDocumentMock, $existingDocumentMock)
            ->willReturn($beforeFlushEventMock);

        $this->eventDispatcherMock->expects($this->exactly(2))
            ->method('dispatch');

        $this->documentManagerMock->expects($this->once())
            ->method('persist');

        $this->documentManagerMock->expects($this->once())
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
        $this->mapperMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $mappedDocumentMock = $this->createMock(Product::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getId')
            ->willReturn('897D6FGSD');

        $this->mapperMock->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $this->documentManagerMock->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $existingDocumentMock = null;

        $beforeFlushEventMock = $this->generateBeforeFlushEventMock($mappedDocumentMock, $existingDocumentMock, true);

        $this->outboundMessageBeforeFlushEventBuilderMock->expects($this->once())
            ->method('build')
            ->with($mappedDocumentMock, $existingDocumentMock)
            ->willReturn($beforeFlushEventMock);

        $this->eventDispatcherMock->expects($this->exactly(1))
            ->method('dispatch');

        $this->documentManagerMock->expects($this->never())
            ->method('persist');

        $this->documentManagerMock->expects($this->never())
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
