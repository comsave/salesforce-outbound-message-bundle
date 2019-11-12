<?php

namespace Comsave\SalesforceOutboundMessageBundle\Model;

class NotificationResponse
{
    public $Ack = true;

    /**
     * @deprecated
     */
    public function setAct($ack)
    {
        $this->setAck((bool)$ack);

        return $this;
    }

    public function getAck(): bool
    {
        return $this->Ack;
    }

    public function setAck(bool $Ack): self
    {
        $this->Ack = $Ack;

        return $this;
    }
}
