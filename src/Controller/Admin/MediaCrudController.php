<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('title', 'Titre')
                ->setColumns(6),
            TextField::new('description', 'Description')
                ->setColumns(6),
            ImageField::new('imageFile', 'Image')
                ->setColumns(6)
                ->setRequired(false)
                ->setBasePath('uploads/img/articles/pics')
                ->setUploadDir('public/uploads/img/articles/pics')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 1920,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 1900 px.',
                    maxHeight: 1080,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 1080 px.',
                    maxSize: '500k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 500 Ko.',
                    mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                    mimeTypesMessage: 'Seuls les formats jpeg, jpg, png, webp sont acceptés.'
                )),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des Medias')
            ->setPageTitle('edit', 'Modifier un Media')
            ->setPageTitle('new', 'Ajouter un Media')
            ->setPageTitle('detail', 'Voir un Media')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Medias')
            ->setEntityLabelInSingular('Media')
            ->showEntityActionsInlined(true)
            ->setPaginatorPageSize(12);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action){
                return $action->setIcon('fas fa-plus text-success')->setLabel('Ajouter un Media');
            })
            ->update(Crud::PAGE_INDEX, ACtion::DELETE, function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            // On DESACTIVE le bouton DELETE et le bouton NEW
            // ->disable(Action::DELETE, Action::NEW, Action::EDIT)
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            })
            ;
    }
}
