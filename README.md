# Symfony OutboundMessageBundle for Salesforce

Create, update, remove objects in Symfony sent through Salesforce outbound messages. 

[![Release](https://img.shields.io/github/v/release/comsave/salesforce-outbound-message-bundle)](https://github.com/comsave/salesforce-outbound-message-bundle/releases)
[![Travis](https://img.shields.io/travis/comsave/salesforce-outbound-message-bundle)](https://travis-ci.org/comsave/salesforce-outbound-message-bundle)
[![Test Coverage](https://img.shields.io/codeclimate/coverage/comsave/salesforce-outbound-message-bundle)](https://codeclimate.com/github/comsave/salesforce-outbound-message-bundle)

---

## Requirements

This bundle assumes you're using:

1) MongoDB database (and specifically [`doctrine/mongodb-odm`](https://github.com/doctrine/mongodb-odm)).
2) [`comsave/salesforce-mapper-bundle`](https://github.com/comsave/salesforce-mapper-bundle) for Salesforce object mapping to your MongoDB `Document` classes.

## Bundle features

* Object `create`
* Object `update`
* Object `delete`. To enable this complete [additional setup steps](README-setup-removal.md).
* Object custom handling `beforeFlush`
* Object custom handling `afterFlush`

## Installation

* ```composer require comsave/salesforce-outbound-message-bundle``` 
* Register the bundle in your `AppKernel.php` by adding 
```new Comsave\SalesforceOutboundMessageBundle\ComsaveSalesforceOutboundMessageBundle() ```
* To handle the Salesforce's incoming outbound messages create a route (for example `/sync`) and a method to a controller: 
```php
<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Comsave\SalesforceOutboundMessageBundle\Services\RequestHandler\OutboundMessageRequestHandler;

class OutboundMessageController extends Controller
{
    public function syncAction(Request $request, OutboundMessageRequestHandler $requestHandler)
    {
        try {
            $outboundMessageXml = $request->getContent();
            return $requestHandler->handle($outboundMessageXml);
        }
        catch (\Throwable $e) {
            throw new \SoapFault("Server", $e->getMessage());
        }
    }
}
```
* add the bundle configuration in your `app/config/config.yml`
```yaml
comsave_salesforce_outbound_message:
    # WSDL_CACHE_NONE, WSDL_CACHE_DISK, WSDL_CACHE_MEMORY or WSDL_CACHE_BOTH
    wsdl_cache: 'WSDL_CACHE_DISK'                     
    # An absolute path to Salesforce object WSDL files
    wsdl_directory: '/absolute/path/' 
    document_paths:
        # Map a document using its Salesforce name and your local class 
        CustomObject__c:              
            path: 'YourNamespace\Documents\CustomObject'
            force_compare: false # if true, incoming object will be compared to existing ones in the database; will continue sync only if not equal
```
* Add `DocumentInterface` to the document class you'd like to be tracked by the `OutboundMessageBundle`.
```php
<?php

use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface;
use LogicItLab\Salesforce\MapperBundle\Model\Account as BaseAccount;

class Account extends BaseAccount implements DocumentInterface
{
}
```
* Create an `EventSubscriber` for an object you'd like to sync. It would look something like this for the `Account` object:
```php
<?php

namespace YourNamespace\EventSubscriber;

use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\SalesforceOutboundMessageBundle\Event\OutboundMessageAfterFlushEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Comsave\SalesforceOutboundMessageBundle\Interfaces\DocumentInterface; 
use Comsave\Webservice\Core\UserBundle\Document\Account;

class AccountSoapRequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            OutboundMessageBeforeFlushEvent::NAME => [
                ['onBeforeFlush'],
            ],
            OutboundMessageAfterFlushEvent::NAME => [
                ['onAfterFlush'],
            ],
        ];
    }

    public function supports(DocumentInterface $document): bool
    {
        $documentClass = get_class($document);

        return Account::class == $documentClass || $document instanceof Account;
    }

    public function onBeforeFlush(OutboundMessageBeforeFlushEvent $event)
    {
        /**
         * Make sure to do call $this->supports() before you start processing the object
         * You only want to process the correct object in this EventSubscriber (which is Account in this case)
         */
        /** @var Account $newAccount */
        $newAccount = $event->getNewDocument();
        
        if (!$this->supports($newAccount)) return; 
    
        /** @var Account $existingAccount */
        $existingAccount = $event->getExistingDocument();
        
        /**
         * You can do any modifications you want to the object before it get's saved (flushed) to the database.
         * - - -
         * $event->getExistingDocument() provides you access to the existing object (if it exists) 
         * $event->getNewDocument() provides you access to the new object delivered by the outbound message. This is the object that will be merged over the existing one (if any) and saved to the database. In most of the cases you only need to use this one.
         */
    }

    public function onAfterFlush(OutboundMessageAfterFlushEvent $event)
    {
        /** @var Account $account */
        $account = $event->getDocument();

        if (!$this->supports($account)) return; 

        /**
         * You can process the object further if necessary after it has been saved (flushed) to the database.
         */
    }
}
```
* Add your newly created route to the Salesforce outbound message for the object you want to sync (`Account` in our example).
* That's it! Trigger an outbound message to be sent out and see everything happen automagically. 😎 👍

## License

This project is licensed under the MIT License.
