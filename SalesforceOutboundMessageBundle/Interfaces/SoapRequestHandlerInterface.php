<?php

namespace SalesforceOutboundMessageBundle\Interfaces;

use SalesforceOutboundMessageBundle\Model\NotificationRequest;
use SalesforceOutboundMessageBundle\Model\NotificationResponse;

interface SoapRequestHandlerInterface
{
    public function notifications(NotificationRequest $notifications): NotificationResponse;
}