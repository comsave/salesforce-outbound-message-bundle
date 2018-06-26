<?php

namespace Comsave\SalesforceOutboundMessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ComsaveSalesforceOutboundMessageBundle:Default:index.html.twig');
    }
}
