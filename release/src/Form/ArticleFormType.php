<?php

namespace App\Form;

use App\Entity\ArticleCategory;
use Symfony\Component\Form\AbstractType;
use App\Entity\Article;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', FileType::class, [
                'required' => false,
                'data_class' => null
            ])
            ->add('title', TextType::class, ['required' => false])
            ->add('content', TextareaType::class, ['required' => false])
            ->add('is_published', CheckboxType::class, ['required' => false])
            ->add('ArticleCategory', EntityType::class, [
                'class' => ArticleCategory::class,
                'choice_label' => 'label',
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ])
            ->add('user_id', TextType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}