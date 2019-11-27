<?php

namespace Comsave\SalesforceOutboundMessageBundle\Services\Builder;

use Symfony\Component\HttpFoundation\Response;

class SoapResponseBuilder
{
    private const SOAP_RESPONSE_HEADERS = [
        'Content-Type' => 'text/xml; charset=ISO-8859-1',
    ];

    public function build(string $responseContent): Response
    {
        $response = new Response();

        foreach (static::SOAP_RESPONSE_HEADERS as $headerKey => $headerValue) {
            $response->headers->set($headerKey, $headerValue);
        }

        return $response->setContent($responseContent);
    }
}