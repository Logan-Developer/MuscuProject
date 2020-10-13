<?php

namespace App\Form\write;

use App\Entity\HeadingPages;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddModifyHeadingPagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titlePage', TextType::class, [
                'label' => 'Titre de l\'article',
                'data' => $options['titleHeadingPage']
            ])
            ->add('contentPage', CKEditorType::class, [
                'label' => "Contenu",
                'data' => $options['contentHeadingPage']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HeadingPages::class,
            'titleHeadingPage'=> '',
            'contentHeadingPage'=> '',
        ]);
    }
}
