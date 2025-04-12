<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use App\Repository\CommentRepository;
use App\Repository\HomePageRepository;
use App\Repository\LinkRepository;
use App\Repository\SettingRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        HomePageRepository $homePageRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $articles = $articleRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 6);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $homePage = $homePageRepository->findOneBy([]);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $links = $linkRepository->findBy(['isActive' => true], []);

        $citation = $citationRepository->findRandom();

        // Articles features and active
        $articlesFeatured = $articleRepository->findBy(['isFeatured' => true, 'isActive' => true], ['createdAt' => 'DESC'], 3);

        // Articles most views and active
        $articlesMostViews = $articleRepository->findBy(['isActive' => true], ['views' => 'DESC'], 3);

        // Total views of all articles
        $totalViews = $articleRepository->totalViews();

        // 3 articles les plus likés avec la fonction findMostLikedArticles
        $articlesMostLikes = $articleRepository->findMostLikedArticles();

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'categories' => $categories,
            'articles' => $articles,
            'articlesFeatured' => $articlesFeatured,
            'articlesMostViews' => $articlesMostViews,
            'articlesMostLikes' => $articlesMostLikes,
            'comments' => $comments,
            'homePage' => $homePage,
            'socials' => $socials,
            'citation' => $citation,
            'links' => $links,
            'total_views' => $totalViews,
            'page_title' => 'Home',
            'seoTitle' => $homePage->getSeoTitle(),
            'seoDescription' => $homePage->getSeoDescription(),
            'seoUrl' => ''
        ]);
    }
}
