<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\Link;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Social;
use App\Entity\Article;
use App\Entity\CguPage;
use App\Entity\Comment;
use App\Entity\Setting;
use App\Entity\Category;
use App\Entity\Citation;
use App\Entity\HomePage;
use App\Entity\Blacklist;
use App\Entity\AproposPage;
use App\Entity\ContactPage;
use App\Repository\TagRepository;
use App\Repository\LinkRepository;
use App\Repository\UserRepository;
use App\Repository\MediaRepository;
use App\Repository\SocialRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use App\Repository\BlacklistRepository;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private UserRepository $userRepository,
        private ArticleRepository $articleRepository,
        private CategoryRepository $categoryRepository,
        private TagRepository $tagRepository,
        private BlacklistRepository $blacklistRepository,
        private SocialRepository $socialRepository,
        private MediaRepository $mediaRepository,
        private CommentRepository $commentRepository,
        private SettingRepository $settingRepository,
        private CitationRepository $citationRepository,
        private LinkRepository $linkRepository
    )
    {
    }
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');

        $users = $this->userRepository->findAll();
        $articles = $this->articleRepository->findBy(['isActive' => true], []);
        $categories = $this->categoryRepository->findBy(["isActive" => true], []);
        $tags = $this->tagRepository->findBy(["isActive" => true], []);
        $blacklists = $this->blacklistRepository->findAll();
        $socials = $this->socialRepository->findBy(['isActive' => true], []);
        $medias = $this->mediaRepository->findAll();
        $comments = $this->commentRepository->findBy(['isActive' => true], []);
        $settings = $this->settingRepository->findOneBy([]);
        $citations = $this->citationRepository->findBy(['isActive' => true], []);
        $links = $this->linkRepository->findBy(['isActive' => true], []);

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'articles' => $articles,
            'categories' => $categories,
            'tags' => $tags,
            'blacklists' => $blacklists,
            'socials' => $socials,
            'medias' => $medias,
            'comments' => $comments,
            'settings' => $settings,
            'citations' => $citations,
            'links' => $links
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('<div style="width: 90%; margin-bottom: 15px; padding: 2px 5px; border: solid 1px #fff; border-radius: 7px; background-color: #ffffff69;"><h1 style="font-size: clamp(20px, 2vw, 30px); font-weight: 800; color: #fff; text-align: center; margin: 0;">Citizen<span style="color: #ea580c;">Z</span></h1></div>')
            ->renderContentMaximized();
    }

    // pour afficher l'avatar du user dans easyadmin...
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User) {
            throw new \Exception('Mauvais utilisateur');
        }
        $image = '../../../uploads/img/users/' . $user->getImage();
        return parent::configureUserMenu($user)
            ->setAvatarUrl($image);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Visiter le site', 'fas fa-home', $this->generateUrl('app_home'));
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-cog');

        // -------------------------------------
        // PAGES
        // -------------------------------------
        yield MenuItem::submenu('Pages')->setCssClass('text-warning fw-bold shadow')->setSubItems([
            MenuItem::linkToCrud('Accueil', 'fas fa-home', HomePage::class)->setAction('detail')->setEntityId(1),
            MenuItem::linkToCrud('A propos', 'fas fa-info', AproposPage::class)->setAction('detail')->setEntityId(1),
            MenuItem::linkToCrud('CGU', 'fas fa-home', CguPage::class)->setAction('detail')->setEntityId(1),
            MenuItem::linkToCrud('Contact', 'fas fa-envelope', ContactPage::class)->setAction('detail')->setEntityId(1),
        ]);

        // -------------------------------------
        // BLOG
        // -------------------------------------
        yield MenuItem::submenu('Blog')->setCssClass('text-warning fw-bold shadow')->setSubItems([
            MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Article::class),
            MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class),
            MenuItem::linkToCrud('Tags', 'fas fa-tags', Tag::class),
            MenuItem::linkToCrud('Commentaires', 'fas fa-comments', Comment::class),
        ]);

        // -------------------------------------
        // IMAGES
        // -------------------------------------
        yield MenuItem::submenu('Images & Médias')->setCssClass('text-warning fw-bold shadow')->setSubItems([
            MenuItem::linkToCrud('Images des articles', 'fas fa-images', Media::class),
        ]);

        // -------------------------------------
        // LIENS WEB
        // -------------------------------------
        yield MenuItem::submenu('Liens web')->setCssClass('text-warning fw-bold shadow')->setSubItems([
            MenuItem::linkToCrud('Liens web', 'fas fa-link', Link::class),
        ]);

        // -------------------------------------
        // PARAMETRES
        // -------------------------------------
        yield MenuItem::submenu('Paramètres')->setCssClass('text-warning fw-bold shadow')->setSubItems([
            MenuItem::linkToCrud('Configuration du site', 'fa fa-cogs', Setting::class),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class),
            MenuItem::linkToCrud('Blackisting email', 'fas fa-ban', Blacklist::class),
            MenuItem::linkToCrud('Reseaux sociaux', 'fab fa-facebook', Social::class),
            MenuItem::linkToCrud('Citations', 'fas fa-quote-right', Citation::class),
        ]);
    }
    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addAssetMapperEntry('admin');
    }
}
