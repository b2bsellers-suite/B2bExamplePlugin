<?php

namespace B2bExamplePlugin\Subscriber;

use B2bExamplePlugin\Setup\PlatformMenuInstaller;
use B2bSellersCore\Components\B2bPlatform\PlatformMenu\Event\PlatformMenuRebuildEvent;
use Doctrine\DBAL\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PlatformMenuSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            PlatformMenuRebuildEvent::class => 'rebuildPlatformMenu'
        ];
    }

    /**
     * @throws Exception
     */
    public function rebuildPlatformMenu(PlatformMenuRebuildEvent $event)
    {
        (new PlatformMenuInstaller())->install($event->getConnection());
    }
}