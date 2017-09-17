<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Ajouter les champs du formulaire
        $builder
            ->add('title', TextType::class,array('label' => 'comment.create.comment.title'))
            ->add('content', TextareaType::class,array('label' => 'comment.create.comment.content'))
            ->add('send', SubmitType::class,array('label' => 'comment.create.comment.submit'))
        ;
    }
}

?>