<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Informations'),
            // IdField::new('id'),
            TextField::new('firstname', 'Prénom')
                ->setColumns(3),
            TextField::new('lastname', 'Nom')
                ->setColumns(3),
            EmailField::new('email', 'Adresse e-mail')
                ->setColumns(3),
            ChoiceField::new('roles', 'Rôle')
                ->setColumns(3)
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ])
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_USER' => 'info',
                    'ROLE_ADMIN' => 'success'
                ]),
            FormField::addTab('Image de profil'),
            ImageField::new('image', 'Image de profil')
                ->setColumns(6)
                ->setBasePath('uploads/img/users')
                ->setUploadDir('public/uploads/img/users')
                ->setRequired(false)
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 500,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 500 px.',
                    maxHeight: 500,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 500 px.',
                    maxSize: '150k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 150 Ko.',
                    mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                    mimeTypesMessage: 'Seuls les formats jpeg, jpg, png, webp sont acceptés.'
                )),

            FormField::addTab('Bio'),
            TextareaField::new('bio', 'Biographie courte')
                ->setColumns(12)
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des utilisateurs')
            ->setPageTitle('edit', 'Modifier un utilisateur')
            ->setPageTitle('detail', 'Profil')
            ->setDefaultSort(['lastname' => 'ASC'])
            ->setPaginatorPageSize(12)
            ->showEntityActionsInlined(true)
            ->setEntityLabelInSingular('utilisateur')
            ->setEntityLabelInPlural('utilisateurs')
            ->renderContentMaximized()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL, Action::DELETE)
            // On DESACTIVE le bouton NEW
            ->disable(Action::NEW)

            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT, function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX,Action::DELETE, function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('');
            });
    }
}
