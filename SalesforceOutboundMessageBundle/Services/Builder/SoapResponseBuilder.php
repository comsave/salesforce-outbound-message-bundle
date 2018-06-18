<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\Builder;

use Symfony\Component\HttpFoundation\Response;

class SoapResponseBuilder
{
    public function build(string $responseContent): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        return $response->setContent($responseContent);
    }
}