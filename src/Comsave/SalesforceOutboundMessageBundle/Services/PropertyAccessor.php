<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @todo moveout to a separate repo
 */
class PropertyAccessor
{
    /** @var PropertyAccess */
    private $propertyAccess;

    public function __construct()
    {
        $this->propertyAccess = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $document
     * @param $fieldName
     * @return mixed
     */
    public function getValue($document, $fieldName)
    {
        return $this->propertyAccess->getValue($document, $fieldName);
    }

    /**
     * @param $document
     * @param $fieldName
     * @param $value
     */
    public function setValue($document, $fieldName, $value)
    {
        return $this->propertyAccess->setValue($document, $fieldName, $value);
    }

    /**
     * @param $document
     * @param $fieldName
     * @return bool
     */
    public function isReadable($document, $fieldName)
    {
        return $this->propertyAccess->isReadable($document, $fieldName);
    }

    /**
     * @param $document
     * @param $fieldName
     * @return bool
     */
    public function isWritable($document, $fieldName)
    {
        return $this->propertyAccess->isWritable($document, $fieldName);
    }
}