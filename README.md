# Salesforce outbound message bundle

The Salesforce outbound message bundle will read and save your Salesforce outbound messages for you. 

## Getting Started

Follow the steps below to quickly set up the project and start processing your Salesforce outbound messages automatically. 

### Prerequisites

This project currently only works with the doctrine ODM and the Salesforce mapper bundle. You can download these packages with the following commands:

```bash
$ composer require comsave/salesforce-mapper-bundle
$ composer require doctrine/mongodb-odm
```
### Installing

After meeting the Prerequisites you can install this project with the following command: 

```bash
   $ composer require comsave/salesforce-outbound-message-bundle
```

From the endpoint that receives your outbound message you can simply grab the raw post data from the request and pass it along to the OutboundMessageRequestHandler. 

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

In order for your documents to be readable, they should implement the DocumentInterface included in this bundle.

If you want to add custom actions to your outbound message you can do so by listening to the OutboundMessageBeforeFlushEvent.

## License

This project is licensed under the MIT License.