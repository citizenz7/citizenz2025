<?php

namespace App\Controller;

use App\Form\RechercheArticleType;
use App\Repository\LinkRepository;
use App\Repository\SocialRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search')]
    public function index(
        SettingRepository $settingRepository,
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository,
        Request $request,
        PaginatorInterface $paginator,
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $citation = $citationRepository->findRandom();
        $links = $linkRepository->findBy(['isActive' => true], []);
        // Total views of all articles
        $totalViews = $articleRepository->totalViews();

        $searchForm = $this->createForm(RechercheArticleType::class);
        $searchForm->handleRequest($request);

        $donnees = $articleRepository->findArticles();

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $title = $searchForm->getData()->getTitle();
            $donnees = $articleRepository->search($title);
        }

        $articles = $paginator->paginate(
            $donnees, // Doctrine Query, not results
            $request->query->getInt('page', 1), // Define the page parameter
            10 // Items per page
        );

        return $this->render('search/index.html.twig', [
            'settings' => $settings,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'citation' => $citation,
            'links' => $links,
            'total_views' => $totalViews,
            'page_title' => 'Recherche',
            'seoTitle' => 'Recherche',
            'seoDescription' => 'Recherche',
            'seoUrl' => $this->generateUrl('app_search'),
            'articles' => $articles,
            'searchForm' => $searchForm,
        ]);
    }
}
