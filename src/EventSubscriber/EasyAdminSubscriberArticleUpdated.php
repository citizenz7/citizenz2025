<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

class EasyAdminSubscriberArticleUpdated implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => ['setArticleUpdatedAt'],
        ];
    }

    public function setArticleUpdatedAt(BeforeEntityUpdatedEvent $event): void
    {
        $article = $event->getEntityInstance();

        if (!$article instanceof Article) {
            return;
        }

        $article->setUpdatedAt(new \DateTimeImmutable());
    }
}