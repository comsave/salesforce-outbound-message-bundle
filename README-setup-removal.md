
## on delete trigger; what why how

Why this way?
How does it work?

Salesforce: Create custom object `ObjectToBeRemoved` (needs to be in your `.wsdl` file as well)
```java 
    text 18  ObjectId__c
    text 100 ObjectClass__c
```

Add the ObjectToBeRemoved object to the outbound messages.


Salesforce: Add class ObjectsToRemoveScheduler
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

add trigger for every object you want to tract deletion for
```java
trigger SomeObjectYoureTrackingTrigger on SomeObjectYoureTracking (after delete, after insert, after undelete, after update, before delete, before insert, before update) {
    if (Trigger.isBefore && Trigger.isDelete) {
        ObjectsToRemoveScheduler.scheduleForRemoval(Trigger.old);
    }
}
```

Add this document to your config.

```yaml
comsave_salesforce_outbound_message:
    document_paths:
      ObjectToBeRemoved__c:
        path: 'Comsave\SalesforceOutboundMessageBundle\Document\ObjectToBeRemoved'
```
