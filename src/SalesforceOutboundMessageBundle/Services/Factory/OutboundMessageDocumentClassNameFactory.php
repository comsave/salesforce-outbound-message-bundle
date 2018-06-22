<?php

namespace SalesforceOutboundMessageBundle\Services\Factory;

use SalesforceOutboundMessageBundle\Exception\SalesforceException;

class OutboundMessageDocumentClassNameFactory
{
    /**
     * @var array
     */
    protected $documentLocations;

    public function __construct(array $documentLocations)
    {
        $this->documentLocations = $documentLocations;
    }
    /**
     * @param string $objectName
     * @return string
     */
    public function getClassName(string $objectName): string
    {
        return $this->documentLocations[$objectName];
//        switch($objectName) {
//            case 'Delivery__c':
//                return Delivery::class;
//            case 'DiscountRule__c':
//                return DiscountRule::class;
//            case 'Order':
//                return Order::class;
//            case 'OrderItem':
//                return OrderProduct::class;
//            case 'Product2':
//                return Product::class;
//            case 'Interconnect__c':
//                return Interconnect::class;
//            case 'Account':
//                return Account::class;
//            case 'MandatoryProductRule__c':
//                return MandatoryProductRule::class;
//            case 'PortalUser__c':
//                return User::class;
//            case 'Opportunity':
//                return Opportunity::class;
//            default:
//                throw new SalesforceException(sprintf('Entity `%s` class not found.', $objectName));
//        }
    }
}