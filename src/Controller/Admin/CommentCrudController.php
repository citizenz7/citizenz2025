<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('nickname', 'Pseudo'),
            TextEditorField::new('content', 'Text du commentaire')
                ->hideOnIndex()
                ->setColumns(12),
            EmailField::new('email', 'E-mail')
                ->setColumns(6),
            DateTimeField::new('createdAt', 'Posté le')
                ->hideOnForm(),
            AssociationField::new('article', 'Article du commentaire'),
            BooleanField::new('rgpd', 'RGPD validé')
                ->setDisabled(true),
            BooleanField::new('isActive', 'Commentaire validé'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des Commentaires')
            ->setPageTitle('edit', 'Modifier un Commentaire')
            ->setPageTitle('new', 'Ajouter un Commentaire')
            ->setPageTitle('detail', 'Voir un Commentaire')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Medias')
            ->setEntityLabelInSingular('Media')
            ->showEntityActionsInlined(true)
            ->setPaginatorPageSize(12);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // On DESACTIVE le bouton NEW
            ->disable(Action::NEW,)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, ACtion::DELETE, function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            })
            ;
    }
}