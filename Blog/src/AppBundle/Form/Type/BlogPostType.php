<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Ajouter les champs du formulaire
        $builder
            ->add('title', TextType::class,array('label' => 'blogpost.create.post.title'))
            ->add('content', TextareaType::class,array('label' => 'blogpost.create.post.content'))
            ->add('url_flickr',TextType::class,array('label' => 'blogpost.create.post.url_flickr'))
            ->add('cover', FileType::class,array('label' => 'blogpost.create.post.cover'))
            ->add('send', SubmitType::class,array('label' => 'blogpost.create.post.submit'))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BlogPost::class,
        ));
    }
}

?>