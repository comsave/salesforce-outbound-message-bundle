<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services;

class PropertyAccessor
{
    /**
     * @param $document
     * @param $fieldName
     * @return mixed
     */
    public function getValue($document, $fieldName)
    {
        if (method_exists($document, $fieldName)) {
            return $document->$fieldName();
        }

        $getter = $this->getPropertyGetter($fieldName);

        return $document->$getter();
    }

    /**
     * @param $document
     * @param $fieldName
     * @param $value
     */
    public function setValue($document, $fieldName, $value)
    {
        $setter = $this->getPropertysetter($fieldName);

        $document->$setter($value);
    }

    /**
     * @param $document
     * @param $fieldName
     * @return bool
     */
    public function isReadable($document, $fieldName)
    {
        $getter = $this->getPropertyGetter($fieldName);

        return (method_exists($document, $getter) || method_exists($document, $fieldName));
    }

    /**
     * @param $document
     * @param $fieldName
     * @return bool
     */
    public function isWritable($document, $fieldName)
    {
        $setter = $this->getPropertySetter($fieldName);

        return method_exists($document, $setter);
    }

    /**
     * @param $fieldName
     * @return string
     */
    private function getPropertyGetter($fieldName)
    {
        return 'get'.ucfirst($fieldName);
    }

    /**
     * @param $fieldName
     * @return string
     */
    private function getPropertySetter($fieldName)
    {
        return 'set'.ucfirst($fieldName);
    }
}