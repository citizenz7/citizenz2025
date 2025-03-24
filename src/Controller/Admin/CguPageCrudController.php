<?php

namespace App\Controller\Admin;

use App\Entity\CguPage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CguPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CguPage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Infos générales')
                ->setIcon('info')
                ->setHelp('Les infos de base de la page'),
            // IdField::new('id'),
            TextField::new('mainTitle', 'Titre')
                ->setColumns(6),
            SlugField::new('slug', 'Slug')
                ->setColumns(6)
                ->hideOnIndex()
                ->setTargetFieldName('mainTitle'),
            TextEditorField::new('content', 'Contenu')
                ->setColumns(12)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('content', 'Contenu')
                ->setColumns(12)
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),

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
            ->setPageTitle('index', 'Page CGU')
            ->setPageTitle('edit', 'Modifier la page CGU')
            ->setPageTitle('detail', 'Page CGU')
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
