<?php

namespace App\Controller\Admin;

use App\Entity\AproposPage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class AproposPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AproposPage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Infos générales')
                ->setIcon('info')
                ->setHelp('Les infos de base de la page'),
            TextField::new('mainTitle', 'Titre')
                ->setColumns(6),
            SlugField::new('slug')
                ->setColumns(6)
                ->hideOnIndex()
                ->setTargetFieldName('mainTitle'),
            TextField::new('subtitle', 'Sous-titre')
                ->setColumns(6),
            TextEditorField::new('content', 'Contenu')
                ->setColumns(12)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('content', 'Contenu')
                ->setColumns(12)
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),
            ImageField::new('image', 'Image')
                ->setColumns(6)
                ->setRequired(false)
                ->setBasePath('uploads/img/pages')
                ->setUploadDir('public/uploads/img/pages')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 1920,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 1920 px.',
                    maxHeight: 1080,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 1080 px.',
                    maxSize: '500k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 500 Ko.',
                    mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                    mimeTypesMessage: 'Seuls les formats jpeg, jpg, png, webp sont acceptés.'
                )),
            FormField::addTab('SEO')
                ->setIcon('fas fa-search')
                ->setHelp('Informations pour les moteurs de recherche. Les deux champs suivants sont obligatoires.'),
            TextField::new('seoTitle', 'Titre SEO')
                ->setColumns(12)
                ->onlyOnForms()
                ->setHelp('Le titre affiché dans les moteurs de recherche. Idéal : 70 caractères maxi. Vous pouvez reprendre le titre de l\'article et l\'adapter si besoin.'),
            TextareaField::new('seoDescription', 'Description SEO')
                ->setColumns(12)
                ->onlyOnForms()
                ->setHelp('La description affichée dans les moteurs de recherche. 160 caractères maxi. Il s\'agit d\'une description en 1 phrase ou 2 qui va mettre en valeur au moins un mot-clé important de l\'article.'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Page A propos')
            ->setPageTitle('edit', 'Modifier la page A propos')
            ->setPageTitle('detail', 'Page A propos')
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
