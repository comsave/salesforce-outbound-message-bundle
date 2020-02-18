<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageAfterFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\ObjectComparator;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder
 */
class SoapRequestHandlerBuilderTest extends TestCase
{
    /**
     * @var SoapRequestHandlerBuilder
     */
    protected $soapRequestHandlerBuilder;

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

    /** @var LoggerInterface|MockObject */
    private $logger;

    public function setUp()
    {
        $this->documentManagerMock = $this->createMock(DocumentManager::class);
        $this->mapperMock = $this->createMock(Mapper::class);
        $this->documentUpdaterMock = $this->createMock(DocumentUpdater::class);
        $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $this->outboundMessageBeforeFlushEventBuilderMock = $this->createMock(OutboundMessageBeforeFlushEventBuilder::class);
        $this->outboundMessageAfterFlushEventBuilderMock = $this->createMock(OutboundMessageAfterFlushEventBuilder::class);
        $this->objectComparatorMock = $this->createMock(ObjectComparator::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->soapRequestHandlerBuilder = new SoapRequestHandlerBuilder(
            $this->documentManagerMock,
            $this->mapperMock,
            $this->documentUpdaterMock,
            $this->eventDispatcherMock,
            $this->outboundMessageBeforeFlushEventBuilderMock,
            $this->outboundMessageAfterFlushEventBuilderMock,
            $this->objectComparatorMock,
            $this->logger
        );
    }

    /**
     * @covers ::build()
     */
    public function testBuildReturnsASoapRequestHandler()
    {
        $objectName = 'Product';

        $soapRequestHandler = $this->soapRequestHandlerBuilder->build($objectName, false);

        $this->assertInstanceOf(SoapRequestHandler::class, $soapRequestHandler);
    }
}
