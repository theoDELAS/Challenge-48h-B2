<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, $this->getConfiguration('Email', 'Votre nouvelle adresse mail'))
            ->add('password', PasswordType::class, $this->getConfiguration('Mot de passe', 'Votre nouveau mot de passe'))
            ->add('username', TextType::class, $this->getConfiguration('Pseudo', 'Votre nouveau pseudo'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
