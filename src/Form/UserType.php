<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre email: ',
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'max' => 4096,
                        ]),
                        new Regex(
                            pattern:'/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/',
                            message: 'Le mot de passe doit contenir 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial. Longueur entre 8 et 16 caractères.'
                        )
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                ],
                'mapped' => false,
            ]);
            if ($options['isAdmin']) {
                $builder
                    ->remove('password')
                    ->add('roles', ChoiceType::class, [
                        'label' => 'Rôle',
                        'choices' => [
                            'Utilisateur' => 'ROLE_USER',
                            'Editeur' => 'ROLE_EDITOR',
                            'Administrateur' => 'ROLE_ADMIN'
                        ],
                        'expanded' => true,
                        'multiple' => true,
                    ]);
            }

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isAdmin' => false,
        ]);
    }
}
