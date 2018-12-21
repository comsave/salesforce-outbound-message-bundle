<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Services\Builder\OutboundMessageBeforeFlushEventBuilder;
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
        $this->assertInstanceOf(OutboundMessageBeforeFlushEvent::class, $this->outboundMessageBeforeFlushEventBuilder->build());
    }
}