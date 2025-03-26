<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\LinkRepository;
use App\Repository\SocialRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categorie')]
final class CategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        SocialRepository $socialRepository,
        CommentRepository $commentRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $links = $linkRepository->findBy(['isActive' => true], []);

        $citation = $citationRepository->findRandom();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'settings' => $settings,
            'socials' => $socials,
            'comments' => $comments,
            'citation' => $citation,
            'links' => $links,
            'page_title' => 'Categories',
            'seoTitle' => 'Catégories',
            'seoDescription' => 'Tous les catégories d\'articles',
            'seoUrl' => $this->generateUrl('app_category_index')
        ]);
    }

    // #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $category = new Category();
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($category);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('category/new.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function show(
        // Category $category
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository,
        CommentRepository $commentRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository,
        string $slug
    ): Response {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        $links = $linkRepository->findBy(['isActive' => true], []);

        $citation = $citationRepository->findRandom();

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'settings' => $settings,
            'categories' => $categories,
            'socials' => $socials,
            'comments' => $comments,
            'citation' => $citation,
            'links' => $links,
            'page_title' => $category->getTitle(),
            'seoTitle' => $category->getTitle(),
            'seoDescription' => $category->getTitle(),
            'seoUrl' => $this->generateUrl('app_category_show', ['slug' => $category->getSlug()])
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('category/edit.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    // public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($category);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    // }
}
