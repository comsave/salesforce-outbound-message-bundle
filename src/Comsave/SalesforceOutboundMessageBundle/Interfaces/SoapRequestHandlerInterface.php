<?php

namespace Comsave\SalesforceOutboundMessageBundle\Interfaces;

use Comsave\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use Comsave\SalesforceOutboundMessageBundle\Model\NotificationResponse;

interface SoapRequestHandlerInterface
{
    public function notifications(NotificationRequest $notifications): NotificationResponse;
}