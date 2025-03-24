<?php

namespace App\Controller;

use App\Repository\SocialRepository;
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
        AproposPageRepository $aproposPageRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $aproposPage = $aproposPageRepository->findOneBy([]);
        $citation = $citationRepository->findRandom();

        return $this->render('apropos/index.html.twig', [
            'settings' => $settings,
            'aproposPage' => $aproposPage,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'citation' => $citation,
            'page_title' => 'Apropos',
            'seoTitle' => html_entity_decode($aproposPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($aproposPage->getSeoDescription()),
            'seoUrl' => $this->generateUrl('app_apropos')
        ]);
    }
}
