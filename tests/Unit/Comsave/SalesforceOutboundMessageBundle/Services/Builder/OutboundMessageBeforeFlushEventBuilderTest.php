<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder;
use LogicItLab\Salesforce\MapperBundle\Model\AbstractModel;
use PHPUnit\Framework\TestCase;

/**
 * Class OutboundMessageBeforeFlushEventBuilderTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder
 */
class OutboundMessageBeforeFlushEventBuilderTest extends TestCase
{
    /**
     * @var OutboundMessageBeforeFlushEventBuilder
     */
    private $outboundMessageBeforeFlushEventBuilder;

    public function setUp()
    {
        $this->outboundMessageBeforeFlushEventBuilder = new OutboundMessageBeforeFlushEventBuilder();
    }
    /**
     * @covers ::build()
     */
    public function testBuild()
    {
        $this->createMock(OutboundMessageBeforeFlushEvent::class);
        $newDocumentMock = $this->createMock(AbstractModel::class);
        $existingDocumentMock = $this->createMock(AbstractModel::class);

        $beforeFlushEventMock = $this->outboundMessageBeforeFlushEventBuilder->build($newDocumentMock, $existingDocumentMock);

        $this->assertInstanceOf(OutboundMessageBeforeFlushEvent::class, $beforeFlushEventMock);
        $this->assertEquals($newDocumentMock, $beforeFlushEventMock->getNewDocument());
        $this->assertEquals($existingDocumentMock, $beforeFlushEventMock->getExistingDocument());
    }
}