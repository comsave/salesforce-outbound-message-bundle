<?php

namespace Tests\Functional\Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageAfterFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder;
use Comsave\SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use Comsave\SalesforceOutboundMessageBundle\Services\ObjectComparator;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\Common\Cache\Cache;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Annotation\AnnotationReader;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use Phpforce\SoapClient\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tests\Stub\DocumentWithSalesforceFields;

/**
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
     * @var Mapper
     */
    private $mapper;

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

    /** @var AnnotationReader */
    private $salesforceAnnotationReader;

    public function setUp(): void
    {
        $this->documentManagerMock = $this->createMock(DocumentManager::class);
        $this->documentUpdaterMock = $this->createMock(DocumentUpdater::class);
        $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $this->outboundMessageBeforeFlushEventBuilderMock = $this->createMock(OutboundMessageBeforeFlushEventBuilder::class);
        $this->outboundMessageAfterFlushEventBuilderMock = $this->createMock(OutboundMessageAfterFlushEventBuilder::class);
        $this->objectComparatorMock = $this->createMock(ObjectComparator::class);

        $this->salesforceAnnotationReader = new AnnotationReader(new \Doctrine\Common\Annotations\AnnotationReader());
        $this->mapper = new Mapper(
            $this->createMock(Client::class),
            $this->salesforceAnnotationReader,
            $this->createMock(Cache::class)
        );

        $this->soapRequestHandler = new SoapRequestHandler(
            $this->documentManagerMock,
            $this->mapper,
            $this->documentUpdaterMock,
            $this->eventDispatcherMock,
            'Product2',
            false,
            $this->outboundMessageBeforeFlushEventBuilderMock,
            $this->outboundMessageAfterFlushEventBuilderMock,
            $this->objectComparatorMock,
            $this->salesforceAnnotationReader
        );
    }

    /** @covers ::getAllowedProperties */
    public function testGetAllowedProperties(): void
    {
        $this->assertEquals([
            'someField',
            'someOtherField'
        ], $this->soapRequestHandler->getAllowedProperties(DocumentWithSalesforceFields::class));
    }
}