<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Controller;

use Comsave\Webservice\Core\SalesforceBundle\Exception\SalesforceException;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Services\RequestHandler\OutboundMessageRequestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class OutboundMessageController
 * @package Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Controller
 */
class OutboundMessageController extends Controller
{
    /**
     * @Rest\Post("/sync")
     * @Rest\View()
     * @throws \SoapFault
     */
    public function indexAction(Request $request, OutboundMessageRequestHandler $requestHandler, LoggerInterface $logger)
    {
        $logger->info('processing outboundmessage');

        try {
            return $requestHandler->handle($request->getContent());
        }
        catch (SalesforceException $e) {
            $logger->error(sprintf('SoapServer: %s', $e->getMessage()));

            throw new \SoapFault("Server", $e->getMessage());
        }
        catch (\Throwable $e) {
            $logger->critical(sprintf('SoapServer (unexpected): %s in %s on %s', $e->getMessage(), $e->getFile(), $e->getLine()));

            throw new \SoapFault("Server", 'An unexpected error has occurred.');
        }
    }
}
