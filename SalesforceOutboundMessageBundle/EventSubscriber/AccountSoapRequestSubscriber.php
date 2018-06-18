<?php

namespace Comsave\Webservice\Core\SalesforceOutboundMessageBundle\EventSubscriber;

use Comsave\Webservice\Core\CoreBundle\Document\Interconnect;
use Comsave\Webservice\Core\CoreBundle\Enumerable\PricingLevel;
use Comsave\Webservice\Core\CoreBundle\Interfaces\DocumentInterface;
use Comsave\Webservice\Core\SalesforceOutboundMessageBundle\Event\OutboundMessageBeforeFlushEvent;
use Comsave\Webservice\Core\UserBundle\Document\Account;
use Comsave\Webservice\Core\UserBundle\Document\User;
use Comsave\Webservice\Core\UserBundle\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Debug\Tests\Fixtures\InternalClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AccountSoapRequestSubscriber
 * @package Comsave\Webservice\Core\SalesforceOutboundMessageBundle\EventSubscriber
 */
class AccountSoapRequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @param DocumentManager $documentManager
     * @codeCoverageIgnore
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            OutboundMessageBeforeFlushEvent::NAME => [
                ['onBeforeFlush'],
            ],
        ];
    }

    public function supports(DocumentInterface $document): bool
    {
        $documentClass = get_class($document);

        return Account::class == $documentClass;
    }

    /**
     * @param OutboundMessageBeforeFlushEvent $event
     * @throws \Comsave\Webservice\Core\CoreBundle\Exception\ArrayRequiredException
     */
    public function onBeforeFlush(OutboundMessageBeforeFlushEvent $event)
    {
        /**
         * @var Account $account
         */
        $account = $event->getDocument();

        if (!$this->supports($account)) return;

        if (!$account->getPricingLevel()) {
            $account->setPricingLevel(PricingLevel::CUSTOMER);
        }

        $users = $this->documentManager->getRepository(User::class)->findBy(['sfAccountId' => $account->getId()]);

        if ($users) {
            /**
             * @var User $user
             */
            foreach ($users as $user) {
                $user->setAccount($account);
            }
        }

        $interconnects = $this->documentManager->getRepository(Interconnect::class)->findBy(['accountId' => $account->getId()]);

        if ($interconnects) {
            /**
             * @var Interconnect $interconnect
             */
            foreach ($interconnects as $interconnect) {
                $interconnect->setAccount($account);
            }
        }
    }
}