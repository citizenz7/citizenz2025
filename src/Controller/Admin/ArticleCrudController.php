<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Controller\Admin\MediaCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Infos générales'),
            // IdField::new('id'),
            BooleanField::new('isActive', 'Actif'),
            BooleanField::new('isFeatured', 'En vedette'),
            IntegerField::new('views', 'Vues')
                ->setcolumns(2),
            TextField::new('title', 'Titre')
                ->setColumns(5),
            SlugField::new('slug', 'Slug')
                ->setTargetFieldName('title')
                ->hideOnIndex()
                ->setColumns(5),
            AssociationField::new('category', 'Catégorie de l\'article')
                ->setHelp('Choisissez une catégorie pour l\'article.')
                ->setRequired(true)
                ->setColumns(4),
            AssociationField::new('author', 'Auteur')
                ->setColumns(2)
                ->onlyOnForms()
                ->onlyWhenUpdating(),
            AssociationField::new('author', 'Auteur')
                ->onlyOnIndex(),
            AssociationField::new('tags', 'Tags')
                ->setHelp('Choisissez des tags pour l\'article.')
                ->setColumns(6),

            FormField::addTab('Textes'),
            TextEditorField::new('intro', 'Introduction')
                ->setColumns(12)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('intro', 'Intro')
                ->hideOnForm()
                ->hideOnIndex(),
            TextEditorField::new('content', 'Contenu')
                ->setColumns(12)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('content', 'Contenu')
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),

            FormField::addTab('Images'),
            ImageField::new('imageFeatured', 'Image principale')
                ->setColumns(6)
                ->setRequired(false)
                ->setBasePath('uploads/img/articles')
                ->setUploadDir('public/uploads/img/articles')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 1920,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 1920 px.',
                    maxHeight: 1280,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 1280 px.',
                    maxSize: '500k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 500 Ko.',
                    mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                    mimeTypesMessage: 'Seuls les formats jpeg, jpg, png, webp sont acceptés.'
                )),
            CollectionField::new('images', 'Galerie d\'images (facultatif)')
                ->setHelp('Ajoutez des images à l\'article (galerie d\'images). Taille de l\'image 1920x1080 maxi. Poids de l\'image 500 Ko maxi.')
                ->setColumns(12)
                ->setRequired(false)
                ->hideOnIndex()
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(MediaCrudController::class),
            DateField::new('createdAt', 'Création')
                ->hideOnForm()
                ->setColumns(2),
            DateField::new('updatedAt', 'Mis à jour le')
                ->setColumns(2)
                ->hideOnForm(),

            FormField::addTab('SEO')
                ->setHelp('Informations pour les moteurs de recherche. Les deux champs suivants sont obligatoires.'),
            TextField::new('seoTitle', 'Titre SEO')
                ->setColumns(12)
                ->hideOnIndex()
                ->setHelp('Le titre affiché dans les moteurs de recherche. Idéal : 70 caractères maxi. Vous pouvez reprendre le titre de l\'article et l\'adapter si besoin.'),
            TextareaField::new('seoDescription', 'Description SEO')
                ->setColumns(12)
                ->hideOnIndex()
                ->setHelp('La description affichée dans les moteurs de recherche. 160 caractères maxi. Il s\'agit d\'une description en 1 phrase ou 2 qui va mettre en valeur au moins un mot-clé important de l\'article.'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des articles')
            ->setPageTitle('edit', 'Modifier un article')
            ->setPageTitle('new', 'Ajouter un article')
            ->setPageTitle('detail', 'Voir un article')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Articles')
            ->setEntityLabelInSingular('Article')
            ->showEntityActionsInlined(true)
            ->setPaginatorPageSize(10);
    }

    public function configureActions(Actions $actions): Actions{
        return $actions
            ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action){
                return $action->setIcon('fas fa-tags pe-1')->setLabel('Ajouter un article');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            })
            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->update(Crud::PAGE_INDEX,Action::DETAIL,function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX,Action::DELETE,function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('');
            });
    }
}
