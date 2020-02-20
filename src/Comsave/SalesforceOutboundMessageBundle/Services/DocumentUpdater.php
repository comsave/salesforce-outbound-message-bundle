<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use TypeError;

/**
 * @todo moveout to a separate repo
 */
class DocumentUpdater
{
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var array
     */
    private $ignoredProperties = ['id'];

    /**
     * @param PropertyAccessor $propertyAccessor
     * @param AnnotationReader $annotationReader
     * @codeCoverageIgnore
     */
    public function __construct(PropertyAccessor $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param $document
     * @param array $newValues
     * @throws TypeError
     */
    public function updateWithValues($document, array $newValues)
    {
        foreach ($newValues as $propertyName => $newValue) {
            $isIgnoredProperty = in_array(strtolower($propertyName), $this->ignoredProperties);

            if (!$isIgnoredProperty && $this->propertyAccessor->isWritable($document, $propertyName)) {
                $this->propertyAccessor->setValue($document, $propertyName, $newValue);
            }
        }
    }

    /**
     * @throws ReflectionException
     * @throws TypeError
     */
    public function updateWithDocument($document, $newDocument, ?array $allowedProperties = [], ?array $ignoredProperties = [])
    {
        $classReflection = new ReflectionClass($newDocument);
        $allowedProperties = $allowedProperties ?: [];
        $ignoredProperties = $ignoredProperties ?: [];
        $checkAllowedProperties = count($allowedProperties) > 0;
        $checkIgnoredProperties = count($ignoredProperties) > 0;

        foreach ($classReflection->getProperties() as $propertyReflection) {
            $propertyName = $propertyReflection->getName();

            if ($checkAllowedProperties && !in_array($propertyName, $allowedProperties)) {
                continue;
            }

            if ($checkIgnoredProperties && in_array($propertyName, $ignoredProperties)) {
                continue;
            }

            if (in_array(strtolower($propertyName), $this->ignoredProperties)) {
                continue;
            }

            if ($this->propertyAccessor->isReadable($newDocument, $propertyName)) {
                $newValue = $this->propertyAccessor->getValue($newDocument, $propertyName);

                if ($newValue !== null && $this->propertyAccessor->isWritable($document, $propertyName)) {
                    $this->propertyAccessor->setValue($document, $propertyName, $newValue);
                }
            }
        }
    }
}