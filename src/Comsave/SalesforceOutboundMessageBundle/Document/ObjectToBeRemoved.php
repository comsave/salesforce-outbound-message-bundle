<?php

namespace Comsave\SalesforceOutboundMessageBundle\Document;

use LogicItLab\Salesforce\MapperBundle\Annotation as Salesforce;

/**
 * @Salesforce\SObject(name="PortalUser__c")
 */
class ObjectToBeRemoved
{
    /**
     * @var string
     * @Salesforce\Field(name="Id")
     */
    private $id;

    /**
     * @var string
     * @Salesforce\Field(name="ObjectId__c")
     */
    private $objectId;

    /**
     * @var string
     * @Salesforce\Field(name="ObjectClass__c")
     */
    private $objectClass;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getObjectId(): string
    {
        return $this->objectId;
    }

    public function setObjectId(string $objectId): void
    {
        $this->objectId = $objectId;
    }

    public function getObjectClass(): string
    {
        return $this->objectClass;
    }

    public function setObjectClass(string $objectClass): void
    {
        $this->objectClass = $objectClass;
    }
}