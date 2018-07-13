<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Phpforce\SalesforceBundle\PhpforceSalesforceBundle::class => ['all' => true],
    LogicItLab\Salesforce\MapperBundle\LogicItLabSalesforceMapperBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle::class => ['dev' => true, 'test' => true],
    Comsave\SalesforceOutboundMessageBundle\ComsaveSalesforceOutboundMessageBundle::class => ['all' => true],
];
