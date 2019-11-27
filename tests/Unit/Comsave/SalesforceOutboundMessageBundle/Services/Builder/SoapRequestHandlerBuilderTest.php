<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageAfterFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SoapRequestHandlerBuilderTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder
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

        $this->soapRequestHandlerBuilder = new SoapRequestHandlerBuilder(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->eventDispatcher,
            $this->logger,
            $this->outboundMessageBeforeFlushEventBuilder,
            $this->outboundMessageAfterFlushEventBuilder
        );
    }

    /**
     * @covers ::build()
     */
    public function testBuildReturnsASoapRequestHandler()
    {
        $objectName = 'Product';

        $soapRequestHandler = $this->soapRequestHandlerBuilder->build($objectName);

        $this->assertInstanceOf(SoapRequestHandler::class, $soapRequestHandler);
    }
}
