<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Class DocumentUpdater
 * @package Comsave\SalesforceOutboundMessageBundle\Services
 */
class DocumentUpdater
{
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $ignoredProperties = ['id'];

    /**
     * @param PropertyAccessor $propertyAccessor
     * @param AnnotationReader $annotationReader
     * @codeCoverageIgnore
     */
    public function __construct(PropertyAccessor $propertyAccessor, AnnotationReader $annotationReader)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param $document
     * @param array $newValues
     * @throws \TypeError
     */
    public function updateWithValues($document, array $newValues)
    {
        foreach ($newValues as $propertyName => $newValue) {
            if (!in_array(strtolower($propertyName), $this->ignoredProperties) && $this->propertyAccessor->isWritable($document, $propertyName)) {
                $this->propertyAccessor->setValue($document, $propertyName, $newValue);
            }
        }
    }

    /**
     * @param $document
     * @param $newDocument
     * @throws \ReflectionException
     * @throws \TypeError
     */
    public function updateWithDocument($document, $newDocument)
    {
        $classReflection = new \ReflectionClass($newDocument);

        foreach ($classReflection->getProperties() as $propertyReflection) {
            if (in_array(strtolower($propertyReflection->getName()), $this->ignoredProperties)) continue;

            if ($this->propertyAccessor->isReadable($newDocument, $propertyReflection->getName())) {
                $newValue = $this->propertyAccessor->getValue($newDocument, $propertyReflection->getName());
            }

            if ($newValue !== null
                && $this->propertyAccessor->isWritable($document, $propertyReflection->getName())
            ) {
                $this->propertyAccessor->setValue($document, $propertyReflection->getName(), $newValue);
            }
        }
    }
}