<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration('Prénom', 'Votre prénom'))
            ->add('lastName', TextType::class, $this->getConfiguration('Nom', 'Votre nom de famille'))
            ->add('email', EmailType::class, $this->getConfiguration('Email', 'Votre adresse mail'))
            ->add('picture', UrlType::class, $this->getConfiguration('Photo de profil', 'URL de votre photo. ex : https://randomuser.me/portraits/men/13.jpg', ['required' => false]))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Une courte introduction vous concernant', ['required' => false]))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', 'Un texte vous décrivant', ['required' => false]))
            ->add('balance', NumberType::class, $this->getConfiguration('Solde', 'Nouveau solde'))
            ->add('userRoles', CollectionType::class)
            ->add('birthday', TextType::class, $this->getConfiguration('Date de naissance', 'Date de naissance'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
