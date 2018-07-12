<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Exception;

class DocumentNotFoundException extends SalesforceException
{
    protected $message = 'You are trying to access a document that could not be found. Did you forget to add the document path to your config file?';
}