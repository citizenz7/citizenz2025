<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriberArticleViews implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setArticleViews'],
        ];
    }

    public function setArticleViews(BeforeEntityPersistedEvent $event): void
    {
        $blog = $event->getEntityInstance();
        if (!($blog instanceof Article)) {
            return;
        }

        $blog->setViews('1');
    }
}