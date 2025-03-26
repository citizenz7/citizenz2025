<?php

namespace App\Controller;

use App\Entity\Blacklist;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Repository\LinkRepository;
use Symfony\Component\Mime\Address;
use App\Repository\SocialRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactPageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        CommentRepository $commentRepository,
        SocialRepository $socialRepository,
        ContactPageRepository $contactPageRepository,
        Request $request,
        MailerInterface $mailer,
        EntityManagerInterface $em,
        CitationRepository $citationRepository,
        LinkRepository $linkRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $categories = $categoryRepository->findBy(["isActive" => true], ['title' => 'ASC']);
        $comments = $commentRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 5);
        $socials = $socialRepository->findBy(['isActive' => true], ['id' => 'ASC']);
        $contactPage = $contactPageRepository->findOneBy([]);
        $links = $linkRepository->findBy(['isActive' => true], []);

        $citation = $citationRepository->findRandom();

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        $siteEmail = $settings->getSiteEmail();
        $siteName = $settings->getSiteName();

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            // Vérifie si l'adresse e-mail est dans la blacklist
            $blacklist = $em->getRepository(Blacklist::class)->findOneBy(['email' => $contactFormData['email']]);

            if ($blacklist) {
                $this->addFlash('error', 'L\'adresse e-mail est blacklistée ! Le message n\'a pas pu être envoyé.');
                return $this->redirect(
                    $this->generateUrl('app_contact') . '#error'
                );
            } else {
                $email = (new Email())
                ->from($siteEmail)
                ->to(new Address($siteEmail, $siteName))
                ->subject('Message depuis votre site web : '. $siteName)
                ->html(
                    '<h4 style="color: #007bff;">Message envoyé depuis le site web : '. $siteName . '</h4>' .
                    '<span style="color: #007bff; font-weight: bold;">De :</span> ' . $contactFormData['nom'] . '<br>' .
                    '<span style="font-weight: bold; color: #007bff;">E-mail :</span> ' . $contactFormData['email'] . '<br>' .
                    '<p><span style="font-weight: bold; color: #007bff;">Message</span> : <br>' . trim(nl2br($contactFormData['message'])) . '</p>',
                    'text/plain'
                );

                try {
                    $mailer->send($email);

                    $this->addFlash('success', 'Le message a bien été envoyé !');
                    return $this->redirect(
                        $this->generateUrl('app_contact') . '#success'
                    );
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du message : ' . $e->getMessage());
                    return $this->redirectToRoute('app_contact');
                }
            }
        }

        return $this->render('contact/index.html.twig', [
            'settings' => $settings,
            'categories' => $categories,
            'comments' => $comments,
            'socials' => $socials,
            'contactPage' => $contactPage,
            'citation' => $citation,
            'links' => $links,
            'page_title' => 'Contact',
            'form' => $form,
            'seoTitle' => html_entity_decode($contactPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($contactPage->getSeoDescription()),
            'seoUrl' => $this->generateUrl('app_contact')
        ]);
    }
}
