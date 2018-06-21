<?php

namespace Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\Webservice\Core\CoreBundle\Document\Product;
use Comsave\Webservice\Core\CoreBundle\Services\DocumentUpdater;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageDocumentClassNameFactory;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\RequestHandler\SoapRequestHandler;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicItLab\Salesforce\MapperBundle\Mapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SoapRequestHandlerBuilderTest
 * @package Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder\SoapRequestHandlerBuilder
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
        $this->outboundMessageEntityClassNameFactory = $this->createMock(OutboundMessageDocumentClassNameFactory::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->soapRequestHandlerBuilder = new SoapRequestHandlerBuilder(
            $this->documentManager,
            $this->mapper,
            $this->documentUpdater,
            $this->outboundMessageEntityClassNameFactory,
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

        $this->outboundMessageEntityClassNameFactory->expects($this->once())
            ->method('getClassName')
            ->with($objectName)
            ->willReturn(Product::class);

        $soapRequestHandler = $this->soapRequestHandlerBuilder->build($objectName);

        $this->assertInstanceOf(SoapRequestHandler::class, $soapRequestHandler);
    }
}
