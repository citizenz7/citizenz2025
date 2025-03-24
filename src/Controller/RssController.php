<?php

namespace App\Controller;

use App\Rss\Rss;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RssController extends AbstractController
{
    #[Route('/rss', name: 'app_rss')]
    public function rssAction(
        EntityManagerInterface $em
    ) {
        // Ftech 5 last articles order by date
        $article = $em->getRepository(Article::class)->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);

        $response = new Response();
        $response->headers->set("Content-type", "text/xml");
        $response->setContent(Rss::generate($article));
        return $response;
    }
}