<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Interfaces;

use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Model\NotificationResponse;

interface SoapRequestHandlerInterface
{
    public function notifications(NotificationRequest $notifications): NotificationResponse;
}