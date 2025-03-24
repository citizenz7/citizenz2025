<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EasyAdminSubscriberArticleAuthor implements EventSubscriberInterface
{
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setArticleAuthor']
        ];
    }

    public function setArticleAuthor(BeforeEntityPersistedEvent $event): void
    {
        $article = $event->getEntityInstance();

        if (!$article instanceof Article) {
            return;
        }

        $user = $this->tokenStorage->getToken()?->getUser();

        if (!$user instanceof User) {
            return;
        }

        $article->setAuthor($user);
    }
}