<?php

namespace Tests\Unit\SalesforceOutboundMessageBundle\Services\Builder;

use SalesforceOutboundMessageBundle\Services\DocumentUpdater;
use SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder;
use SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SoapRequestHandlerBuilderTest
 * @package Tests\Unit\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder
 */
class SoapRequestHandlerBuilderTest extends TestCase
{
    /**
     * @var SoapRequestHandlerBuilder
     */
    protected $soapRequestHandlerBuilder;

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
    private $outboundMessageEntityClassNameFactory;

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

        $this->soapRequestHandlerBuilder = new SoapRequestHandlerBuilder(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->eventDispatcher,
            $this->logger
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
