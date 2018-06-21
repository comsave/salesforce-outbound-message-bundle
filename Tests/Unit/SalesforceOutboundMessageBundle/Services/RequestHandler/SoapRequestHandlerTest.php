<?php

namespace Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\RequestHandler;

use SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use SalesforceOutboundMessageBundle\Model\NotificationRequest;
use SalesforceOutboundMessageBundle\Model\NotificationResponse;
use SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use Psr\Log\LoggerInterface;

/**
 * Class SoapRequestHandlerTest
 * @package Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\RequestHandler
 * @coversDefaultClass \Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler
 */
class SoapRequestHandlerTest extends TestCase
{
    /**
     * @var SoapRequestHandler
     */
    protected $soapRequestHandler;

    /**
     * @var MockObject
     */
    private $documentManager;

    /**
     * @var MockObject
     */
    private $mapper;

    /**
     * @var MockObject
     */
    private $documentUpdater;

    /**
     * @var MockObject
     */
    private $eventDispatcher;

    /**
     * @var MockObject
     */
    private $logger;

    public function setUp()
    {
        $this->documentManager = $this->createMock(DocumentManager::class);
        $this->mapper = $this->createMock(Mapper::class);
        $this->documentUpdater = $this->createMock(DocumentUpdater::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->soapRequestHandler = new SoapRequestHandler(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->eventDispatcher,
            $this->logger,
            'DiscountRule__c'
        );
    }

    /**
     * @covers ::notifications()
     * @covers ::process()
     * @expectedException \SalesforceOutboundMessageBundle\Exception\SalesforceException
     */
    public function testNotificationsThrowsExceptionWhenNotAnObjectInNotification()
    {
        $notification = new \stdClass();
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
        $notification = new \stdClass();
        $notification->sObject = new \stdClass();

        $notificationRequestMock = $this->createMock(NotificationRequest::class);
        $notificationRequestMock->expects($this->atLeastOnce())
            ->method('getNotification')
            ->willReturn($notification);

        $mappedDocumentMock = $this->createMock(DocumentInterface::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getName')
            ->willReturn('Ziggo 100Mbit fiber');

        $this->mapper->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $existingDocumentMock = $this->createMock(DocumentInterface::class);

        $this->documentManager->expects($this->once())
            ->method('find')
            ->willReturn($existingDocumentMock);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch');

        $this->documentUpdater->expects($this->once())
            ->method('updateWithDocument');

        $this->documentManager->expects($this->once())
            ->method('flush');

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
        $notification = new \stdClass();
        $notification->sObject = new \stdClass();

        $notificationRequestMock = $this->createMock(NotificationRequest::class);
        $notificationRequestMock->expects($this->atLeastOnce())
            ->method('getNotification')
            ->willReturn($notification);

        $mappedDocumentMock = $this->createMock(DocumentInterface::class);
        $mappedDocumentMock->expects($this->once())
            ->method('getName')
            ->willReturn('Ziggo 100Mbit fiber');

        $this->mapper->expects($this->once())
            ->method('mapToDomainObject')
            ->willReturn($mappedDocumentMock);

        $this->documentManager->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch');

        $this->documentManager->expects($this->once())
            ->method('persist');

        $this->documentManager->expects($this->once())
            ->method('flush');

        $response = $this->soapRequestHandler->notifications($notificationRequestMock);

        $this->assertInstanceOf(NotificationResponse::class, $response);
        $this->assertTrue($response->getAck());
    }
}
