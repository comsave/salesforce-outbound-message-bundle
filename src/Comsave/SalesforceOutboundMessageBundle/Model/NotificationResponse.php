<?php

namespace Comsave\SalesforceOutboundMessageBundle\Model;

class NotificationResponse
{
    public $Ack = true;

    public function getAck()
    {
        return $this->Ack;
    }

    public function setAct($ack)
    {
        $this->Ack = $ack;

        return $this;
    }
}
