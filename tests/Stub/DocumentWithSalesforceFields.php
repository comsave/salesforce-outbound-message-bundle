<?php

namespace Tests\Stub;

use LogicItLab\Salesforce\MapperBundle\Annotation as Salesforce;

class DocumentWithSalesforceFields
{
    /** @var string|null */
    private $id;

    /**
     * @Salesforce\Field(name="SomeField__c")
     * @var string|null
     */
    private $someField;

    /** @var string|null */
    private $someUnmappedField;

    /**
     * @Salesforce\Field(name="SomeOtherField__c")
     * @var string|null
     */
    private $someOtherField;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getSomeField(): ?string
    {
        return $this->someField;
    }

    public function setSomeField(?string $someField): void
    {
        $this->someField = $someField;
    }

    public function getSomeUnmappedField(): ?string
    {
        return $this->someUnmappedField;
    }

    public function setSomeUnmappedField(?string $someUnmappedField): void
    {
        $this->someUnmappedField = $someUnmappedField;
    }

    public function getSomeOtherField(): ?string
    {
        return $this->someOtherField;
    }

    public function setSomeOtherField(?string $someOtherField): void
    {
        $this->someOtherField = $someOtherField;
    }
}