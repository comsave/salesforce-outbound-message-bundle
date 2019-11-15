# Symfony OutboundMessageBundle for Salesforce

Create, update, remove objects in Symfony sent through Salesforce outbound messages. 

---

## Requirements

This bundle is designed to work with these prerequisites:

1) MongoDB database (and specifically [`doctrine/mongodb-odm`](https://github.com/doctrine/mongodb-odm)).
2) [`comsave/salesforce-mapper-bundle`](https://github.com/comsave/salesforce-mapper-bundle)

## Bundle features

* Object `create`
* Object `update`
* Object `delete`. To enable this complete [additional setup steps](README-setup-removal.md).
* Object custom handling `beforeFlush`
* Object custom handling `afterFlush`

## Installation

* ```composer require comsave/salesforce-outbound-message-bundle``` 
* Register the bundle in your `AppKernel.php` by adding ```php new Comsave\SalesforceOutboundMessageBundle\ComsaveSalesforceOutboundMessageBundle() ```
* To handle the Salesforce's incoming outbound messages create a route (for example `/sync`) and a method to a controller: 
```php
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
            path: 'Namespace\Documents\CustomObject'
```
* 

## x
From the endpoint that receives your outbound message you can simply grab the raw post data from the request and pass it along to the `OutboundMessageRequestHandler`. 

Your controller could look something like this:

x

In order for the Salesforce outbound message bundle to know where your wsdl files and documents are located you have to specify these locations in your config.yml file.

The example below shows you what the structure should look like:


In order for your documents to be readable, they should implement the `DocumentInterface` included in this bundle.

If you want to add custom actions to your outbound message you can do so by listening to the `OutboundMessageBeforeFlushEvent` or `OutboudMessageAfterFlushEvent`.


## License

This project is licensed under the MIT License.