<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use App\Repository\SocialRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $links = $linkRepository->findBy(['isActive' => true], []);
        $citation = $citationRepository->findRandom();
        // Total views of all articles
        $totalViews = $articleRepository->totalViews();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'settings' => $settings,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'citation' => $citation,
            'links' => $links,
            'total_views' => $totalViews,
            'page_title' => 'Login',
            'seoUrl' => $this->generateUrl('app_login'),
            'seoTitle' => 'Connexion',
            'seoDescription' => 'Connexion sur le site',
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
