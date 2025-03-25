<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom complet',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-gray-700 text-xs xl:text-sm font-bold mb-2'
                ],
                'attr' => [
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse e-mail',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-gray-700 text-xs xl:text-sm font-bold mb-2'
                ],
                'attr' => [
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4'
                ]
            ])
            ->add('sujet', TextType::class, [
                'label' => 'Sujet de votre message',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-gray-700 text-xs xl:text-sm font-bold mb-2'
                ],
                'attr' => [
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-gray-700 text-sm font-bold mb-2'
                ],
                'attr' => [
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4',
                    'rows' => 10
                ]
            ])
            // ->add('captcha', ReCaptchaType::class)

            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                // 'script_nonce_csp' => $nonceCSP,
                'locale' => 'fr',
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}