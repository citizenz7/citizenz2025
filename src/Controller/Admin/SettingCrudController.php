<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Général')
                ->setIcon('fas fa-info')
                ->setHelp('Informations générales'),
            FormField::addPanel('Titre'),
            TextField::new('siteName', 'Titre du site')
                ->setColumns(6),

            FormField::addPanel('Logo'),
            ImageField::new('siteLogo', 'Logo du site')
                ->setColumns(6)
                ->setRequired(false)
                ->setBasePath('uploads/img')
                ->setUploadDir('public/uploads/img')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 800,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 800 px.',
                    maxHeight: 600,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 600 px.',
                    maxSize: '100k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 100 Ko.',
                    mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                    mimeTypesMessage: 'Seuls les formats jpeg, jpg, png, webp sont acceptés.'
                )),

            FormField::addTab('Url')
                ->setIcon('fas fa-link')
                ->setHelp('Adresses du site'),
            FormField::addPanel('Url'),
            TextField::new('siteUrl', 'Url courte du site')
                ->setColumns(6),
            TextField::new('siteUrlfull', 'Url complète du site')
                ->setColumns(6),

            FormField::addTab('Coordonnées')
                ->setIcon('fas fa-map-marker-alt')
                ->setHelp('Email, adresse, téléphone'),
            FormField::addPanel('Coordonnées'),
            EmailField::new('siteEmail', 'E-mail du site')
                ->setColumns(3),
            TextField::new('siteAdresse', 'Adresse postale')
                ->setColumns(5)
                ->hideOnIndex(),
            TextField::new('siteCp', 'Code Postal')
                ->setColumns(1)
                ->hideOnIndex(),
            TextField::new('siteVille', 'Ville')
                ->setColumns(3)
                ->hideOnIndex(),
            TextField::new('sitePhone', 'Numéro de téléphone')
                ->setColumns(3)
                ->hideOnIndex(),

            FormField::addTab('Description Footer')
                ->setIcon('fas fa-info-circle')
                ->setHelp('Description du site'),
            TextareaField::new('siteFooterdescription', 'Description du footer')
                ->hideOnIndex()
                ->setColumns(12),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Paramètres du site')
            ->setPageTitle('edit', 'Modifier les paramètres')
            ->setPageTitle('detail', 'Paramètres du site')
            ->showEntityActionsInlined(true);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            // On DESACTIVE le bouton DELETE et le bouton NEW
            ->disable(Action::DELETE, Action::NEW)
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            });
    }
}
