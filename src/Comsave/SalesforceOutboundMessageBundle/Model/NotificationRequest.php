<?php

namespace Comsave\SalesforceOutboundMessageBundle\Model;

class NotificationRequest
{
    protected $OrganizationId;
    protected $ActionId;
    protected $SessionId;
    protected $EnterpriseUrl;
    protected $PartnerUrl;
    protected $Notification;

    public function getOrganizationId()
    {
        return $this->OrganizationId;
    }

    public function setOrganizationId($organizationId)
    {
        $this->OrganizationId = $organizationId;

        return $this;
    }

    public function getActionId()
    {
        return $this->ActionId;
    }

    public function setActionId($actionId)
    {
        $this->ActionId = $actionId;
    }

    public function getSessionId()
    {
        return $this->SessionId;
    }

    public function setSessionId($sessionId)
    {
        $this->SessionId = $sessionId;
    }

    public function getEnterpriseUrl()
    {
        return $this->EnterpriseUrl;
    }

    public function setEnterpriseUrl($enterpriseUrl)
    {
        $this->EnterpriseUrl = $enterpriseUrl;
    }

    public function getPartnerUrl()
    {
        return $this->PartnerUrl;
    }

    public function setPartnerUrl($partnerUrl)
    {
        $this->PartnerUrl = $partnerUrl;
    }

    public function getNotification()
    {
        return $this->Notification;
    }

    public function setNotification($notification)
    {
        $this->Notification = $notification;
    }
}