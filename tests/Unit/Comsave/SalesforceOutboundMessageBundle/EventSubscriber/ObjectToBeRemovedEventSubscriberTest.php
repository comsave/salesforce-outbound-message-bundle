<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\EventSubscriber;

use Comsave\SalesforceOutboundMessageBundle\Document\ObjectToBeRemoved;
use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\EventSubscriber\ObjectToBeRemovedEventSubscriber;
use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use Comsave\SalesforceOutboundMessageBundle\Services\Factory\SalesforceObjectDocumentMetadataFactory;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use LogicItLab\Salesforce\MapperBundle\Model\Account;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\EventSubscriber\ObjectToBeRemovedEventSubscriber
 */
class ObjectToBeRemovedEventSubscriberTest extends TestCase
{
    /** @var ObjectToBeRemovedEventSubscriber */
    private $objectToBeRemovedEventSubscriber;

    /** @var MockObject|DocumentManager */
    private $documentManagerMock;

    /** @var MockObject|Mapper */
    private $mapperMock;

    /** @var MockObject|SalesforceObjectDocumentMetadataFactory */
    private $outboundMessageDocumentClassNameFactory;

    public function setUp(): void
    {
        $this->documentManagerMock = $this->createMock(DocumentManager::class);
        $this->mapperMock = $this->createMock(Mapper::class);
        $this->outboundMessageDocumentClassNameFactory = $this->createMock(SalesforceObjectDocumentMetadataFactory::class);

        $this->objectToBeRemovedEventSubscriber = new ObjectToBeRemovedEventSubscriber(
            $this->documentManagerMock,
            $this->mapperMock,
            $this->outboundMessageDocumentClassNameFactory
        );
    }

    /**
     * @covers ::supports()
     */
    public function testSupportsCorrectObject(): void
    {
        $document = new ObjectToBeRemoved();

        $this->assertTrue($this->objectToBeRemovedEventSubscriber->supports($document));
    }

    /**
     * @covers ::supports()
     */
    public function testDoesNotSupportIncorrectObject(): void
    {
        $document = $this->createMock(DocumentInterface::class);

        $this->assertFalse($this->objectToBeRemovedEventSubscriber->supports($document));
    }

    /**
     * @covers ::supports()
     * @covers ::onBeforeFlush()
     */
    public function testRemovesScheduledDocument(): void
    {
        $objectIdStub = '04x1w0000008Ptv';
        $objectClassStub = 'Account';

        $objectToBeRemovedMock = $this->createMock(ObjectToBeRemoved::class);
        $objectToBeRemovedMock->expects($this->any())
            ->method('getObjectId')
            ->willReturn($objectIdStub);
        $objectToBeRemovedMock->expects($this->any())
            ->method('getObjectClass')
            ->willReturn($objectClassStub);

        $eventMock = $this->createMock(OutboundMessageBeforeFlushEvent::class);
        $eventMock->expects($this->once())
            ->method('getNewDocument')
            ->willReturn($objectToBeRemovedMock);

        $objectClassNameStub = 'Comsave\Document\Account';

        $this->outboundMessageDocumentClassNameFactory->expects($this->once())
            ->method('getClassName')
            ->with($objectClassStub)
            ->willReturn($objectClassNameStub);

        $objectMock = $this->createMock(Account::class);
        $objectRepositoryMock = $this->createMock(DocumentRepository::class);

        $this->documentManagerMock->expects($this->once())
            ->method('getRepository')
            ->with($objectClassNameStub)
            ->willReturn($objectRepositoryMock);

        $objectRepositoryMock->expects($this->once())
            ->method('find')
            ->with($objectIdStub)
            ->willReturn($objectMock);

        $this->documentManagerMock->expects($this->once())
            ->method('remove')
            ->with($objectMock);
        $this->documentManagerMock->expects($this->once())
            ->method('flush');

        $this->mapperMock->expects($this->once())
            ->method('delete')
            ->with([$objectToBeRemovedMock]);

        $eventMock->expects($this->once())
            ->method('setSkipDocument')
            ->with(true);

        $this->objectToBeRemovedEventSubscriber->onBeforeFlush($eventMock);
    }

    /**
     * @covers ::supports()
     * @covers ::onBeforeFlush()
     */
    public function testSkipsScheduledDocumentRemovalIfDocumentNotFound(): void
    {
        $objectIdStub = '04x1w0000008Ptv';
        $objectClassStub = 'Account';

        $objectToBeRemovedMock = $this->createMock(ObjectToBeRemoved::class);
        $objectToBeRemovedMock->expects($this->any())
            ->method('getObjectId')
            ->willReturn($objectIdStub);
        $objectToBeRemovedMock->expects($this->any())
            ->method('getObjectClass')
            ->willReturn($objectClassStub);

        $eventMock = $this->createMock(OutboundMessageBeforeFlushEvent::class);
        $eventMock->expects($this->once())
            ->method('getNewDocument')
            ->willReturn($objectToBeRemovedMock);

        $objectClassNameStub = 'Comsave\Document\Account';

        $this->outboundMessageDocumentClassNameFactory->expects($this->once())
            ->method('getClassName')
            ->with($objectClassStub)
            ->willReturn($objectClassNameStub);

        $objectMock = null;
        $objectRepositoryMock = $this->createMock(DocumentRepository::class);

        $this->documentManagerMock->expects($this->once())
            ->method('getRepository')
            ->with($objectClassNameStub)
            ->willReturn($objectRepositoryMock);

        $objectRepositoryMock->expects($this->once())
            ->method('find')
            ->with($objectIdStub)
            ->willReturn($objectMock);

        $this->documentManagerMock->expects($this->never())
            ->method('remove');
        $this->documentManagerMock->expects($this->never())
            ->method('flush');

        $this->mapperMock->expects($this->once())
            ->method('delete')
            ->with([$objectToBeRemovedMock]);

        $eventMock->expects($this->once())
            ->method('setSkipDocument')
            ->with(true);

        $this->objectToBeRemovedEventSubscriber->onBeforeFlush($eventMock);
    }
}
