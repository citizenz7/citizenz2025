<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use App\Repository\LinkRepository;
use App\Repository\SocialRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tag')]
final class TagController extends AbstractController
{
    #[Route(name: 'app_tag_index', methods: ['GET'])]
    public function index(
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        TagRepository $tagRepository,
        LinkRepository $linkRepository
    ): Response {
        $tags = $tagRepository->findAll();
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $links = $linkRepository->findBy(['isActive' => true], []);
        $citation = $citationRepository->findRandom();
        $totalViews = $articleRepository->totalViews();

        return $this->render('tag/index.html.twig', [
            'settings' => $settings,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'citation' => $citation,
            'links' => $links,
            'total_views' => $totalViews,
            'tags' => $tags,
            'page_title' => 'Liste des Tags',
            'seoTitle' => 'Liste des Tags',
            'seoDescription' => 'Liste des Tags',
            'seoUrl' => $this->generateUrl('app_tag_index')
        ]);
    }

    // #[Route('/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $tag = new Tag();
    //     $form = $this->createForm(TagType::class, $tag);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($tag);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('tag/new.html.twig', [
    //         'tag' => $tag,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{slug}', name: 'app_tag_show', methods: ['GET'])]
    public function show(
        // Tag $tag
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        TagRepository $tagRepository,
        LinkRepository $linkRepository,
        string $slug
    ): Response {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $citation = $citationRepository->findRandom();
        $links = $linkRepository->findBy(['isActive' => true], []);
        $tag = $tagRepository->findOneBy(['slug' => $slug]);
        $totalViews = $articleRepository->totalViews();

        return $this->render('tag/show.html.twig', [
            'settings' => $settings,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'citation' => $citation,
            'links' => $links,
            'total_views' => $totalViews,
            'page_title' => $tag->getTitle(),
            'seoTitle' => html_entity_decode($tag->getTitle()),
            'seoDescription' => html_entity_decode($tag->getTitle()),
            'seoUrl' => $this->generateUrl('app_tag_show', ['slug' => $tag->getSlug()]),
            'tag' => $tag,
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(TagType::class, $tag);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('tag/edit.html.twig', [
    //         'tag' => $tag,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_tag_delete', methods: ['POST'])]
    // public function delete(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($tag);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
    // }
}
