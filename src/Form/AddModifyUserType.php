<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddModifyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'data' => $options['firstname']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'data' => $options['lastname']
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'data' => $options['username']
            ])
            ->add('email', EmailType::class, [
                'data' => $options['email']
            ]);

        if ($options['addingUser']) {

            $builder->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas!',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Mot de passe par défaut'],
                'second_options' => ['label' => 'Vérification du mot de passe'],
            ]);
        }

        $builder->add('roles', ChoiceType::class, [
            'choices' => [
                'Utilisateur standard' => 'ROLE_USER',
                'Rédacteur' => 'ROLE_REDACTOR',
                'Coatch' => 'ROLE_COATCH',
                'Administrateur' => 'ROLE_ADMIN',
            ],
            'data' => $options['roles']
        ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;

        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'addingUser' => true,
            'firstname' => '',
            'lastname' => '',
            'username' => '',
            'email' => '',
            'roles' => ['ROLE_USER']
        ]);
    }
}
