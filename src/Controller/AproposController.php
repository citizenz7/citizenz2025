<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use App\Repository\SocialRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use App\Repository\AproposPageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AproposController extends AbstractController
{
    #[Route('/apropos', name: 'app_apropos')]
    public function index(
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        AproposPageRepository $aproposPageRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $aproposPage = $aproposPageRepository->findOneBy([]);
        $citation = $citationRepository->findRandom();
        $links = $linkRepository->findBy(['isActive' => true], []);
        // Total views of all articles
        $totalViews = $articleRepository->totalViews();

        return $this->render('apropos/index.html.twig', [
            'settings' => $settings,
            'aproposPage' => $aproposPage,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'citation' => $citation,
            'links' => $links,
            'total_views' => $totalViews,
            'page_title' => 'Apropos',
            'seoTitle' => html_entity_decode($aproposPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($aproposPage->getSeoDescription()),
            'seoUrl' => $this->generateUrl('app_apropos')
        ]);
    }
}
