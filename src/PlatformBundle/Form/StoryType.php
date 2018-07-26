<?php

namespace PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use PlatformBundle\Form\ImageStoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('category', EntityType::class, array(
                'class'         => 'PlatformBundle:Category',
                'choice_label'  => 'name',
                'label'         => 'Categorie',
                'multiple'      => false
              ))
            ->add('imageStory', ImageStoryType::class, array(
                'required' => false,
                'label'    => "Image d'illustration de l'article (facultatif
                )"
                ))
            ->add('save', SubmitType::class, array('label' => 'Créer un article'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PlatformBundle\Entity\Story',
            'error_mapping' => array(
                '.' => 'fr',
            ),
        ));
    }

    
}