<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use App\Repository\SocialRepository;
use App\Repository\CguPageRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CguController extends AbstractController
{
    #[Route('/cgu', name: 'app_cgu')]
    public function index(
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CguPageRepository $cguPageRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $cguPage = $cguPageRepository->findOneBy([]);
        $links = $linkRepository->findBy(['isActive' => true], []);

        $citation = $citationRepository->findRandom();

        return $this->render('cgu/index.html.twig', [
            'settings' => $settings,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'cguPage' => $cguPage,
            'citation' => $citation,
            'links' => $links,
            'page_title' => 'CGU',
            'seoTitle' => $cguPage->getSeoTitle(),
            'seoDescription' => $cguPage->getSeoDescription(),
            'seoUrl' => $this->generateUrl('app_cgu')
        ]);
    }
}
