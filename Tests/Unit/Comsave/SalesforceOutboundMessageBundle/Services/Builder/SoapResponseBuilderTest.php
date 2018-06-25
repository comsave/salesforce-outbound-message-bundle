<?php

namespace Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapResponseBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SoapResponseBuilderTest
 * @package Tests\Unit\Comsave\SalesforceOutboundMessageBundle\Services\Builder
 * @coversDefaultClass \Comsave\SalesforceOutboundMessageBundle\Services\Builder\SoapResponseBuilder
 */
class SoapResponseBuilderTest extends TestCase
{
    /**
     * @var SoapResponseBuilder
     */
    protected $soapResponseBuilder;

    public function setUp()
    {
        $this->soapResponseBuilder = new SoapResponseBuilder();
    }

    /**
     * @covers ::build()
     */
    public function testBuildReturnsAResponse()
    {
        $response = $this->soapResponseBuilder->build('Request successful');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Request successful', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml; charset=ISO-8859-1', $response->headers->get('content-type'));
    }
}