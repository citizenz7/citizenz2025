<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class LikeController extends AbstractController
{
    #[Route('/article/{id}/like', name: 'article_like', methods: ['POST'])]
    public function like(Request $request, Article $article, EntityManagerInterface $em): JsonResponse
    {
        $ip = $request->getClientIp();

        // Vérifie si l'IP a déjà liké cet article
        $existingLike = $em->getRepository(Like::class)->findOneBy([
            'article' => $article,
            'ipAddress' => $ip
        ]);

        // Définir si l'article est déjà liké
        $liked = $existingLike ? true : false;

        // Ajoute un like si l'utilisateur n'a pas encore liké
        if (!$existingLike) {
            $like = new Like();
            $like->setArticle($article);
            $like->setIpAddress($ip);
            $em->persist($like);
            $em->flush();

            // Rafraîchir l'entité article pour obtenir le bon nombre de likes
            $em->refresh($article);
        } else {
            // Supprime le like existant si l'utilisateur souhaite annuler son like
            $em->remove($existingLike);
            $em->flush();

            // Rafraîchir l'entité article pour obtenir le bon nombre de likes
            $em->refresh($article);
        }

        // Retourne l'état du like (ajouté ou non) et le nombre actuel de likes
        return new JsonResponse([
            'liked' => !$liked,  // Si like ajouté, retourne true, sinon false
            'count' => count($article->getLikes())  // Le bon nombre de likes après modification
        ]);
    }
}
