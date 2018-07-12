<?php

namespace App\Comsave\SalesforceOutboundMessageBundle\Interfaces;

use App\Comsave\SalesforceOutboundMessageBundle\Model\NotificationRequest;
use App\Comsave\SalesforceOutboundMessageBundle\Model\NotificationResponse;

interface SoapRequestHandlerInterface
{
    public function notifications(NotificationRequest $notifications): NotificationResponse;
}