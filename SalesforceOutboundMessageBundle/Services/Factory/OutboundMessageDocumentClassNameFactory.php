<?php

namespace SalesforceOutboundMessageBundle\Services\Factory;

use Comsave\Webservice\Core\CoreBundle\Document\DiscountRule;
use Comsave\Webservice\Core\CoreBundle\Document\Interconnect;
use Comsave\Webservice\Core\CoreBundle\Document\MandatoryProductRule;
use Comsave\Webservice\Core\CoreBundle\Document\Opportunity;
use Comsave\Webservice\Core\CoreBundle\Document\Product;
use Comsave\Webservice\Core\DeliveryBundle\Document\Delivery;
use Comsave\Webservice\Core\OrderBundle\Document\Order;
use Comsave\Webservice\Core\OrderBundle\Document\OrderProduct;
use Comsave\Webservice\Core\UserBundle\Document\Account;
use Comsave\Webservice\Core\UserBundle\Document\User;
use SalesforceOutboundMessageBundle\Exception\SalesforceException;

class OutboundMessageDocumentClassNameFactory
{
    /**
     * @param string $objectName
     * @return string
     * @throws SalesforceException
     */
    public function getClassName(string $objectName): string
    {
        switch($objectName) {
            case 'Delivery__c':
                return Delivery::class;
            case 'DiscountRule__c':
                return DiscountRule::class;
            case 'Order':
                return Order::class;
            case 'OrderItem':
                return OrderProduct::class;
            case 'Product2':
                return Product::class;
            case 'Interconnect__c':
                return Interconnect::class;
            case 'Account':
                return Account::class;
            case 'MandatoryProductRule__c':
                return MandatoryProductRule::class;
            case 'PortalUser__c':
                return User::class;
            case 'Opportunity':
                return Opportunity::class;
            default:
                throw new SalesforceException(sprintf('Entity `%s` class not found.', $objectName));
        }
    }
}