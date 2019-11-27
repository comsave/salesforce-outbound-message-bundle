<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageAfterFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageAfterFlushEventBuilder;
use LogicItLab\Salesforce\MapperBundle\Model\AbstractModel;
use PHPUnit\Framework\TestCase;

/**
 * Class OutboundMessageAfterFlushEventBuilderTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageAfterFlushEventBuilder
 */
class OutboundMessageAfterFlushEventBuilderTest extends TestCase
{
    /**
     * @var OutboundMessageAfterFlushEventBuilder
     */
    private $outboundMessageAfterFlushEventBuilder;

    public function setUp()
    {
        $this->outboundMessageAfterFlushEventBuilder = new OutboundMessageAfterFlushEventBuilder();
    }

    /**
     * @covers ::build()
     */
    public function testBuild()
    {
        $this->createMock(OutboundMessageAfterFlushEvent::class);
        $documentMock = $this->createMock(AbstractModel::class);

        $afterFlushEventMock = $this->outboundMessageAfterFlushEventBuilder->build($documentMock);

        $this->assertInstanceOf(OutboundMessageAfterFlushEvent::class, $afterFlushEventMock);
        $this->assertEquals($documentMock, $afterFlushEventMock->getDocument());
    }
}