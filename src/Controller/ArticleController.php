<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TagRepository;
use App\Repository\LinkRepository;
use App\Repository\SocialRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_articles', methods: ['GET'])]
    public function index(
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository,
        Request $request
    ): Response {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $links = $linkRepository->findBy(['isActive' => true], []);

        $citation = $citationRepository->findRandom();

        // Pagination
        $dql = "SELECT a FROM App\Entity\Article a WHERE a.isActive = 1 ORDER BY a.createdAt DESC";
        $query = $em->createQuery($dql);
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        // Articles features and active
        $articlesFeatured = $articleRepository->findBy(['isFeatured' => true, 'isActive' => true], ['createdAt' => 'DESC'], 4);

        // Articles most views and active
        $articlesMostViews = $articleRepository->findBy(['isActive' => true], ['views' => 'DESC'], 4);

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'comments' => $comments,
            'settings' => $settings,
            'articlesFeatured' => $articlesFeatured,
            'articlesMostViews' => $articlesMostViews,
            'socials' => $socials,
            'categories' => $categories,
            'citation' => $citation,
            'links' => $links,
            'page_title' => 'Blog',
            'seoTitle' => 'Tous les articles',
            'seoDescription' => 'Tous les articles de notre blog',
            'seoUrl' => $this->generateUrl('app_articles'),
        ]);
    }

    // #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $article = new Article();
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($article);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('article/new.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{slug}', name: 'app_article', methods: ['GET', 'POST'])]
    public function show(
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        TagRepository $tagRepository,
        EntityManagerInterface $em,
        Request $request,
        MailerInterface $mailer,
        SocialRepository $socialRepository,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository,
        $slug,
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $article = $articleRepository->findOneBy(['slug' => $slug, 'isActive' => true]);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $tags = $tagRepository->findBy(['isActive' => true], ['title' => 'ASC']);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $links = $linkRepository->findBy(['isActive' => true], []);

        $citation = $citationRepository->findRandom();

        // Articles features and active
        $articlesFeatured = $articleRepository->findBy(['isFeatured' => true, 'isActive' => true], ['createdAt' => 'DESC'], 4);

        // Articles most views and active
        $articlesMostViews = $articleRepository->findBy(['isActive' => true], ['views' => 'DESC'], 4);

        // On renvoie sur Home si l'article est inactif
        if ($article->isActive() == false) {
            return $this->redirectToRoute('app_home');
        }

        // Set +1 view for each visit
        $read = $article->getViews() + 1;
        $article->setViews($read);

        $em->persist($article);
        $em->flush();

        // Comments article : form and persist flush in Comment entity
        $comment = new Comment($article);
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setArticle($article);
            $comment->setNickname($comment->getNickname());
            $comment->setEmail($comment->getEmail());
            $comment->setContent($comment->getContent());
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setIsActive(false);
            $comment->setRgpd(true);

            $em->persist($comment);
            $em->flush();

            // Send email to admin
            $email = (new TemplatedEmail())
                ->from($comment->getEmail())
                ->to($settings->getSiteEmail())
                ->subject('Nouveau commentaire sur le blog : ' . $article->getTitle() . ', par ' . $comment->getNickname())
                ->htmlTemplate('emails/comment.html.twig')
                ->context([
                    'comment' => $comment,
                    'settings' => $settings,
                    'article' => $article
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Merci pour votre commentaire ! Il sera publié après modération.');
            return $this->redirectToRoute('app_article', ['slug' => $article->getSlug()]);
        }

        return $this->render('articles/article.html.twig', [
            'article' => $article,
            'settings' => $settings,
            'comments' => $comments,
            'categories' => $categories,
            'tags' => $tags,
            'articlesFeatured' => $articlesFeatured,
            'articlesMostViews' => $articlesMostViews,
            'commentForm' => $commentForm,
            'socials' => $socials,
            'citation' => $citation,
            'links' => $links,
            'page_title' => 'Article',
            'seoTitle' => $article->getSeoTitle(),
            'seoDescription' => $article->getSeoDescription(),
            'seoUrl' => $this->generateUrl('app_article', ['slug' => $article->getSlug()]),
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('article/edit.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    // public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($article);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    // }
}
