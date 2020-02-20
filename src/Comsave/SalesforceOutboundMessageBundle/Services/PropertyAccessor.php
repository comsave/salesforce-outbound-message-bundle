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
        $getMethodName = $this->getGetterName($document, $fieldName);

        if(method_exists($document, $getMethodName)) {
            return $document->{$getMethodName}();
        }

        return $this->propertyAccess->getValue($document, $fieldName);
    }

    /**
     * @param $document
     * @param $fieldName
     * @param $value
     */
    public function setValue($document, $fieldName, $value)
    {
        $setMethodName = $this->getGetterName($document, $fieldName);

        if(method_exists($document, $setMethodName)) {
            return $document->{$setMethodName}($value);
        }

        return $this->propertyAccess->setValue($document, $fieldName, $value);
    }

    /**
     * @param $document
     * @param $fieldName
     * @return bool
     */
    public function isReadable($document, $fieldName)
    {
        if($this->getGetterName($document, $fieldName) !== null) {
            return true;
        }

        return $this->propertyAccess->isReadable($document, $fieldName);
    }

    /**
     * @param $document
     * @param $fieldName
     * @return bool
     */
    public function isWritable($document, $fieldName)
    {
        if($this->getSetterName($document, $fieldName) !== null) {
            return true;
        }

        return $this->propertyAccess->isWritable($document, $fieldName);
    }

    public function getGetterName($document, $fieldName): ?string
    {
        $getMethodName = sprintf('get%s', ucfirst($fieldName));

        if(method_exists($document, $getMethodName)) {
            return $getMethodName;
        }

        $getMethodName = sprintf('is%s', ucfirst($fieldName));

        if(method_exists($document, $getMethodName)) {
            return $getMethodName;
        }

        return null;
    }

    public function getSetterName($document, $fieldName): ?string
    {
        $setMethodName = sprintf('set%s', ucfirst($fieldName));

        if(method_exists($document, $setMethodName)) {
            return $setMethodName;
        }

        return null;
    }
}