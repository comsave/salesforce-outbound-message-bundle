# Salesforce outbound message bundle

The Salesforce outbound message bundle will read and save your Salesforce outbound messages for you. 

## Prerequisites

This project currently only works with the doctrine ODM and the Salesforce mapper bundle. These packages are included in `composer.json`.

## Installing

After meeting the Prerequisites you can install this project with the following command: 

```bash
   $ composer require comsave/salesforce-outbound-message-bundle
```

From the endpoint that receives your outbound message you can simply grab the raw post data from the request and pass it along to the `OutboundMessageRequestHandler`. 

Your controller could look something like this:

```php
/**
 * @Rest\Post("/sync")
 * @Rest\View()
 */
public function indexAction(Request $request, OutboundMessageRequestHandler $requestHandler)
{
    try {
        return $requestHandler->handle($request->getContent());
    }
    catch (\Throwable $e) {
        //Handle exceptions here...
    }
}
```

In order for the Salesforce outbound message bundle to know where your wsdl files and documents are located you have to specify these locations in your config.yml file.

The example below shows you what the structure should look like:

```yaml
comsave_salesforce_outbound_message:
    wsdl_directory: 'path/with/your/wsdl/files'
    document_paths:
        Account:
            path: 'path/to/document/Account'
        Product2:
            path: 'path/to/document/Product'
```

In order for your documents to be readable, they should implement the `DocumentInterface` included in this bundle.

If you want to add custom actions to your outbound message you can do so by listening to the `OutboundMessageBeforeFlushEvent` or `OutboudMessageAfterFlushEvent`.

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


## Running tests

```bash
   $ composer run-tests
```

## License

This project is licensed under the MIT License.