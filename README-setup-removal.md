# Enabling object removal sync

>This is a workaround. Salesforce only sends outbound messages on create and/or update of an object. Deletion is not available. 

## How it works

What happens in Salesforce:

1) Once an object (for example `Account`) you're tracking gets removed a trigger is triggered.
2) If it's a `isRemove` trigger a custom object called `ObjectToBeRemoved` gets created on the removal. It stores the class name and the ID of the object that is being removed.
3) An outbound message is being send with the newly created `ObjectToBeRemoved` object.

What happens in the OutboundMessageBundle:

1) Using the class name and the ID stored in newly processed `ObjectToBeRemoved` the bundle finds and removes the described object.
2) Finishing up `ObjectToBeRemoved` is removed from Salesforce. 

## Required setup in the OutboundMessageBundle

Add the `ObjectToBeRemoved` document configuration to your `config.yml`.

```yaml
comsave_salesforce_outbound_message:
    document_paths:
      # ... your other document
      ObjectToBeRemoved__c:
        path: 'Comsave\SalesforceOutboundMessageBundle\Document\ObjectToBeRemoved'
```

## Required setup in Salesforce

* create the custom `ObjectToBeRemoved` object.
```java 
    text 18  ObjectId__c
    text 100 ObjectClass__c
```

* create an outbound message for the `ObjectToBeRemoved` object. The `.wsdl` file for this object is included in the `OutboundMessageBundle` and will be loaded automatically.
* create class `ObjectsToRemoveScheduler`
```java 
public without sharing class ObjectsToRemoveScheduler {
    public static void scheduleForRemoval(List<SObject> objectItems) {
        List<ObjectToBeRemoved__c> objectsToBeRemoved = new List<ObjectToBeRemoved__c>();
        
        for (SObject objectItem: objectItems) {
            ObjectToBeRemoved__c objectToBeRemoved = new ObjectToBeRemoved__c(
                ObjectId__c = objectItem.Id,
                ObjectClass__c = String.valueOf(objectItem).substring(0, String.valueOf(objectItem).indexOf(':'))
            );
            
            objectsToBeRemoved.add(objectToBeRemoved);
        }
        
        insert objectsToBeRemoved;
    }
}
```
* create a trigger for every object you want to track deletion of
```java
trigger AccountTrigger on Account (after delete, after insert, after undelete, after update, before delete, before insert, before update) {
    if (Trigger.isBefore && Trigger.isDelete) {
        ObjectsToRemoveScheduler.scheduleForRemoval(Trigger.old);
    }
}
```

* BONUS: It would be wise to add a savepoint for the database. In case an exception happens and the object actually never gets removed in Salesforce. This will allow us to rollback.
```java
// At the start:
Savepoint sp = Database.setSavepoint();

//In the catch:
Database.rollback(sp);
```