<?php

namespace Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\Webservice\Core\CoreBundle\Document\DiscountRule;
use Comsave\Webservice\Core\CoreBundle\Document\Interconnect;
use Comsave\Webservice\Core\CoreBundle\Document\MandatoryProductRule;
use Comsave\Webservice\Core\CoreBundle\Document\Opportunity;
use Comsave\Webservice\Core\CoreBundle\Document\Product;
use Comsave\Webservice\Core\DeliveryBundle\Document\Delivery;
use Comsave\Webservice\Core\OrderBundle\Document\Order;
use Comsave\Webservice\Core\OrderBundle\Document\OrderProduct;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageDocumentClassNameFactory;
use Comsave\Webservice\Core\UserBundle\Document\Account;
use Comsave\Webservice\Core\UserBundle\Document\User;
use PHPUnit\Framework\TestCase;

/**
 * Class OutboundMessageDocumentClassNameFactoryTest
 * @package Tests\Unit\Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory
 * @coversDefaultClass \Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Factory\OutboundMessageDocumentClassNameFactory
 */
class OutboundMessageDocumentClassNameFactoryTest extends TestCase
{
    /**
     * @var OutboundMessageDocumentClassNameFactory
     */
    protected $OutboundMessageDocumentClassNameFactory;

    public function setUp()
    {
        $this->OutboundMessageDocumentClassNameFactory = new OutboundMessageDocumentClassNameFactory();
    }

    public function objectNameProvider()
    {
        return [
            [Delivery::class, 'Delivery__c'],
            [DiscountRule::class, 'DiscountRule__c'],
            [Order::class, 'Order'],
            [OrderProduct::class, 'OrderItem'],
            [MandatoryProductRule::class, 'MandatoryProductRule__c'],
            [Product::class, 'Product2'],
            [Interconnect::class, 'Interconnect__c'],
            [Account::class, 'Account'],
            [User::class, 'PortalUser__c'],
            [Opportunity::class, 'Opportunity']
        ];
    }

    /**
     * @dataProvider objectNameProvider()
     * @covers ::getClassName()
     */
    public function testGetClassNameReturnsRightPathForEachObjectName($expectedClassName, $objectName)
    {
        $this->assertEquals($expectedClassName, $this->OutboundMessageDocumentClassNameFactory->getClassName($objectName));
    }

    /**
     * @covers ::getClassName()
     * @expectedException \Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException
     */
    public function testGetClassNameThrowsExceptionWhenClassIsNotFound()
    {
        $this->OutboundMessageDocumentClassNameFactory->getClassName('NonExistentClassName');
    }
}
