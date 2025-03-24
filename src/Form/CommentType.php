<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => 'Votre Pseudo',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse E-mail',
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre Commentaire',
                'required' => true
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('rgpd', CheckboxType::class, [
                'label' => false,
                'required' => true
            ])
            // ->add('isActive')
            // ->add('article', EntityType::class, [
            //     'class' => Article::class,
            //     'choice_label' => 'id',
            // ])
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'bg-blue-600 text-white p-5 rounded cursor-pointer hover:bg-blue-800 transition-colors duration-300 ease-in-out focus:outline-none'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Comment::class,
        ]);
    }
}
